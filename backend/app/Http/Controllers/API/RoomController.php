<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Convert stored local path into a full valid URL for the frontend.
     */
    private function formatImageUrl($path)
    {
        if (!$path)
            return null;

        // Remove unwanted prefixes
        $clean = ltrim(str_replace(['public/', 'storage/'], '', $path), '/');

        // Produce absolute URL
        return url('storage/' . $clean);
    }

    /**
     * Format image paths for a single room.
     */
    private function formatRoomResponse(Room $room)
    {
        foreach ($room->images as $img) {
            $img->image_path = $this->formatImageUrl($img->image_path);
        }
        return $room;
    }

    /**
     * Display all rooms under a property.
     */
    public function index(Property $property)
    {
        $rooms = $property->rooms()->with('images')->latest()->get();

        // Normalize URLs
        $rooms->transform(function ($room) {
            return $this->formatRoomResponse($room);
        });

        return response()->json($rooms);
    }

    /**
     * 🏠 Create a new room under a property
     */
    public function store(StoreRoomRequest $request, Property $property)
    {
        if (!$property->exists) {
            return response()->json(['message' => 'Invalid property reference'], 400);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Convert empty strings to null
            $data = array_map(function ($value) {
                return (is_string($value) && trim($value) === '') ? null : $value;
            }, $data);

            $data['property_id'] = $property->id;

            // Skip empty/ghost rooms
            $isEmpty =
                empty($data['room_type']) &&
                empty($data['bathroom_type']) &&
                empty($data['bed_type']) &&
                (empty($data['capacity']) || (int) $data['capacity'] === 0);

            if ($isEmpty) {
                return response()->json(['message' => 'Skipped empty room creation'], 400);
            }

            // Create room
            $room = $property->rooms()->create($data);

            // Upload images
            $this->handleImageUpload($request, $room);

            DB::commit();

            return response()->json([
                'message' => 'Room created successfully!',
                'room' => $this->formatRoomResponse($room->load('images')),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✏ Update existing room details
     */
    public function update(Request $request, Property $property, Room $room)
    {
        DB::beginTransaction();

        try {
            if ($room->property_id !== $property->id) {
                return response()->json(['message' => 'Room does not belong to this property'], 400);
            }

            $validated = $request->validate([
                'room_type' => 'nullable|string|max:255',
                'capacity' => 'nullable|integer|min:0',
                'current_occupancy' => 'nullable|integer|min:0',
                'bathroom_type' => 'nullable|string|max:255',
                'bed_type' => 'nullable|string|max:255',
            ]);

            // Nullify empty strings
            $validated = array_map(function ($value) {
                return (is_string($value) && trim($value) === '') ? null : $value;
            }, $validated);

            // Check if update contains meaningful data
            $isEmpty =
                empty($validated['room_type']) &&
                empty($validated['bathroom_type']) &&
                empty($validated['bed_type']) &&
                (empty($validated['capacity']) || (int) $validated['capacity'] === 0);

            if ($isEmpty) {
                return response()->json(['message' => 'Skipping update — no meaningful data'], 400);
            }

            // Update room
            $room->update($validated);

            // Handle new uploads
            if ($request->hasFile('room_images') || $request->hasFile('bathroom_images')) {
                $this->handleImageUpload($request, $room);
            }

            DB::commit();

            return response()->json([
                'message' => 'Room updated successfully!',
                'room' => $this->formatRoomResponse($room->load('images')),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🗑 Delete room
     */
    public function destroy(Property $property, Room $room)
    {
        DB::beginTransaction();

        try {
            if ($room->property_id !== $property->id) {
                return response()->json(['message' => 'Room does not belong to this property'], 400);
            }

            foreach ($room->images as $image) {
                $clean = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($clean);
                $image->delete();
            }

            $room->delete();
            DB::commit();

            return response()->json(['message' => 'Room deleted successfully']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🧹 Force delete room
     */
    public function forceDestroy(Room $room)
    {
        try {
            foreach ($room->images as $image) {
                $clean = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($clean);
                $image->delete();
            }

            $room->delete();

            return response()->json(['message' => 'Room permanently deleted']);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to force delete room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📤 Upload room/bathroom images via AJAX
     */
    public function uploadImages(Request $request, Room $room)
    {
        try {
            $this->handleImageUpload($request, $room);

            return response()->json([
                'message' => 'Images uploaded successfully!',
                'images' => $room->images()->get()->map(function ($img) {
                    $img->image_path = $this->formatImageUrl($img->image_path);
                    return $img;
                }),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to upload images',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🗑 Delete individual image
     */
    public function deleteImage(Room $room, $imageId)
    {
        try {
            $image = $room->images()->findOrFail($imageId);

            $clean = str_replace('/storage/', '', $image->image_path);
            Storage::disk('public')->delete($clean);

            $image->delete();

            return response()->json(['message' => 'Image deleted successfully']);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete image',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔧 Unified upload handler
     */
    private function handleImageUpload(Request $request, Room $room)
    {
        $types = [
            'room_images' => 'room',
            'bathroom_images' => 'bathroom',
            'other_room_images' => 'other',
        ];

        foreach ($types as $input => $type) {
            if ($request->hasFile($input)) {
                foreach ($request->file($input) as $file) {
                    $path = $file->store("room_images/{$type}", 'public');

                    $room->images()->create([
                        'type' => $type,
                        'image_path' => $this->formatImageUrl($path),
                    ]);
                }
            }
        }
    }
}

