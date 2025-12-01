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
     * Display all rooms under a property.
     */
    public function index(Property $property)
    {
        return response()->json(
            $property->rooms()->with('images')->latest()->get()
        );
    }

    /**
     * 🏠 Create new room for a given property
     */
    public function store(StoreRoomRequest $request, Property $property)
    {
        if (!$property || !$property->exists) {
            return response()->json([
                'message' => 'Invalid property reference — cannot create room.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Convert empty strings to null
            $data = array_map(function ($value) {
                if (is_string($value)) {
                    $trimmed = trim($value);
                    return $trimmed === '' ? null : $trimmed;
                }
                return $value;
            }, $data);

            $data['property_id'] = $property->id;

            // Skip ghost/empty rooms
            $isEmpty =
                (empty($data['room_type']) || trim($data['room_type']) === '') &&
                (empty($data['bathroom_type']) || trim($data['bathroom_type']) === '') &&
                (empty($data['bed_type']) || trim($data['bed_type']) === '') &&
                (empty($data['capacity']) || (int) $data['capacity'] === 0);

            if ($isEmpty) {
                return response()->json([
                    'message' => 'Skipped empty room creation.',
                ], 400);
            }

            // Create room
            $room = $property->rooms()->create($data);

            // Handle uploads
            $this->handleImageUpload($request, $room);

            DB::commit();

            return response()->json([
                'message' => 'Room created successfully!',
                'room' => $room->load('images'),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✏️ Update existing room details
     */
    public function update(Request $request, Property $property, Room $room)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'room_type' => 'nullable|string|max:255',
                'capacity' => 'nullable|integer|min:0',
                'current_occupancy' => 'nullable|integer|min:0',
                'bathroom_type' => 'nullable|string|max:255',
                'bed_type' => 'nullable|string|max:255',
            ]);

            // Convert blanks to null
            $validated = array_map(function ($value) {
                return is_string($value) && trim($value) === '' ? null : $value;
            }, $validated);

            // Ensure room belongs to property
            if ($room->property_id !== $property->id) {
                return response()->json(['message' => 'This room does not belong to the specified property.'], 400);
            }

            // Skip if empty
            $isEmpty =
                (empty($validated['room_type']) || trim($validated['room_type']) === '') &&
                (empty($validated['bathroom_type']) || trim($validated['bathroom_type']) === '') &&
                (empty($validated['bed_type']) || trim($validated['bed_type']) === '') &&
                (empty($validated['capacity']) || (int) $validated['capacity'] === 0);

            if ($isEmpty) {
                return response()->json(['message' => 'Skipping update — no meaningful data provided.'], 400);
            }

            $room->update($validated);

            // Handle image upload if included
            if ($request->hasFile('room_images') || $request->hasFile('bathroom_images')) {
                $this->handleImageUpload($request, $room);
            }

            DB::commit();

            return response()->json([
                'message' => 'Room updated successfully!',
                'room' => $room->load('images'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🗑 Delete a room
     */
    public function destroy(Property $property, Room $room)
    {
        DB::beginTransaction();

        try {
            if ($room->property_id !== $property->id) {
                return response()->json(['message' => 'Room does not belong to this property.'], 400);
            }

            foreach ($room->images as $image) {
                $path = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($path);
                $image->delete();
            }

            $room->delete();
            DB::commit();

            return response()->json(['message' => 'Room deleted successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🧹 Force delete room (no property relation check)
     */
    public function forceDestroy(Room $room)
    {
        try {
            foreach ($room->images as $image) {
                $path = str_replace('/storage/', '', $image->image_path);
                Storage::disk('public')->delete($path);
                $image->delete();
            }

            $room->delete();

            return response()->json(['message' => 'Room permanently deleted (force).']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to force delete room.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📤 Upload room/bathroom images via AJAX (from Vue)
     */
    public function uploadImages(Request $request, Room $room)
    {
        try {
            $this->handleImageUpload($request, $room);
            return response()->json([
                'message' => 'Images uploaded successfully!',
                'images' => $room->images()->get(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to upload images.',
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
            $path = str_replace('/storage/', '', $image->image_path);
            Storage::disk('public')->delete($path);
            $image->delete();

            return response()->json(['message' => 'Image deleted successfully.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete image.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔧 Centralized image upload logic
     */
    private function handleImageUpload(Request $request, Room $room)
    {
        $types = [
            'room_images' => 'room',
            'bathroom_images' => 'bathroom',
            'other_room_images' => 'other',
        ];

        foreach ($types as $key => $type) {
            if ($request->hasFile($key)) {
                foreach ($request->file($key) as $file) {
                    $path = $file->store("room_images/{$type}", 'public');
                    $room->images()->create([
                        'type' => $type,
                        'image_path' => Storage::url($path),
                    ]);
                }
            }
        }
    }
}
