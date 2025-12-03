<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\AdditionalRoomDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    /**
     * Convert any stored path into a full valid URL.
     */
    private function formatImageUrl($path)
    {
        if (!$path)
            return null;

        // Clean the path
        $clean = ltrim(str_replace(['public/', 'storage/'], '', $path), '/');

        // Build usable absolute URL
        return url('storage/' . $clean);
    }

    /**
     * 🏠 Store new property
     */
    public function store(StorePropertyRequest $request)
    {
        foreach (['amenities', 'rooms', 'additional_rooms'] as $jsonField) {
            if ($request->has($jsonField) && is_string($request->$jsonField)) {
                $decoded = json_decode($request->$jsonField, true);
                $request->merge([$jsonField => $decoded]);
            }
        }

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $rooms = $data['rooms'] ?? [];
        $additionalRooms = $data['additional_rooms'] ?? [];
        unset($data['rooms'], $data['additional_rooms'], $data['room_overview'], $data['price']);

        DB::beginTransaction();

        try {
            $property = Property::create($data);

            /**
             * PROPERTY IMAGES
             */
            if ($request->hasFile('images')) {
                $uploaded = [];
                foreach ($request->file('images') as $file) {
                    $path = $file->store('property_images', 'public');
                    $uploaded[] = $this->formatImageUrl($path);
                }
                $property->images = json_encode($uploaded, JSON_UNESCAPED_SLASHES);
                $property->save();
            }

            /**
             * ROOMS + ROOM IMAGES
             */
            foreach ($rooms as $roomData) {
                $room = $property->rooms()->create([
                    'room_type' => $roomData['room_type'] ?? null,
                    'capacity' => $roomData['capacity'] ?? null,
                    'current_occupancy' => $roomData['current_occupancy'] ?? 0,
                    'bathroom_type' => $roomData['bathroom_type'] ?? null,
                    'bed_type' => $roomData['bed_type'] ?? null,
                ]);

                // Room images
                if (!empty($roomData['room_images'])) {
                    foreach ($roomData['room_images'] as $file) {
                        $path = $file->store('room_images/room', 'public');
                        RoomImage::create([
                            'room_id' => $room->id,
                            'type' => 'room',
                            'image_path' => $this->formatImageUrl($path),
                        ]);
                    }
                }

                // Bathroom images
                if (!empty($roomData['bathroom_images'])) {
                    foreach ($roomData['bathroom_images'] as $file) {
                        $path = $file->store('room_images/bathroom', 'public');
                        RoomImage::create([
                            'room_id' => $room->id,
                            'type' => 'bathroom',
                            'image_path' => $this->formatImageUrl($path),
                        ]);
                    }
                }
            }

            /**
             * ADDITIONAL ROOMS
             */
            foreach ($additionalRooms as $extraRoom) {
                if (!empty($extraRoom['name']) && !empty($extraRoom['images'])) {
                    foreach ($extraRoom['images'] as $file) {
                        $path = $file->store('additional_rooms', 'public');
                        $property->additionalRooms()->create([
                            'name' => $extraRoom['name'],
                            'image_path' => $this->formatImageUrl($path),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Property created successfully',
                'property' => $this->formatPropertyResponse($property),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✏️ Update Property
     */
    public function update(Request $request, Property $property)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'contact' => 'nullable|string|max:255',
                'available_rooms' => 'sometimes|integer|min:0',
                'tenants_needed' => 'sometimes|integer|min:0',
                'current_tenants' => 'sometimes|integer|min:0',
                'contract_duration' => 'sometimes|string|max:255',
                'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
                'amenities' => 'nullable|array',
                'location_advantages' => 'nullable|string|max:500',
                'nearby_shop' => 'nullable|string|max:255',
                'nearby_facility' => 'nullable|string|max:255',
                'distance_ui_tm' => 'nullable|string|max:255',
            ]);

            $property->update($validated);

            /**
             * Replace property images
             */
            if ($request->hasFile('images')) {
                // Delete old images
                if ($property->images) {
                    $oldImages = json_decode($property->images, true);
                    foreach ($oldImages as $img) {
                        $path = str_replace('/storage/', '', $img);
                        Storage::disk('public')->delete($path);
                    }
                }

                // Save new images
                $uploaded = [];
                foreach ($request->file('images') as $file) {
                    $path = $file->store('property_images', 'public');
                    $uploaded[] = $this->formatImageUrl($path);
                }

                $property->images = json_encode($uploaded, JSON_UNESCAPED_SLASHES);
                $property->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Property updated successfully',
                'property' => $this->formatPropertyResponse($property),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update property',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🧾 Get all properties
     */
    public function index()
    {
        $properties = Property::with(['user', 'rooms.images', 'additionalRooms'])
            ->latest()
            ->get();

        return response()->json($this->formatManyProperties($properties));
    }

    /**
     * 🔍 Show one property
     */
    public function show($id)
    {
        $property = Property::with(['user', 'rooms.images', 'additionalRooms'])
            ->findOrFail($id);

        return response()->json($this->formatPropertyResponse($property));
    }

    /**
     * ❌ Delete property
     */
    public function destroy($id)
    {
        $property = Property::with(['rooms.images', 'additionalRooms'])->findOrFail($id);

        // Delete property images
        if ($property->images) {
            foreach (json_decode($property->images, true) as $img) {
                $path = str_replace('/storage/', '', $img);
                Storage::disk('public')->delete($path);
            }
        }

        // Delete additional rooms
        foreach ($property->additionalRooms as $extra) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $extra->image_path));
            $extra->delete();
        }

        // Delete rooms + images
        foreach ($property->rooms as $room) {
            foreach ($room->images as $img) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $img->image_path));
                $img->delete();
            }
            $room->delete();
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }

    /**
     * 🧩 Additional Room — store
     */
    public function storeAdditionalRooms(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('additional_rooms', 'public');
            $property->additionalRooms()->create([
                'name' => $request->name,
                'image_path' => $this->formatImageUrl($path),
            ]);
        }

        return response()->json([
            'message' => 'Additional room added successfully',
            'rooms' => $property->additionalRooms()->get(),
        ]);
    }

    /**
     * 📝 Additional Room — update
     */
    public function updateAdditionalRoom(Request $request, $id)
    {
        $room = AdditionalRoomDetail::find($id);
        if (!$room) {
            return response()->json(['message' => "Room not found"], 404);
        }

        if ($request->name) {
            $room->name = $request->name;
        }

        if ($request->hasFile('images')) {
            // Delete old image
            Storage::disk('public')->delete(str_replace('/storage/', '', $room->image_path));

            // Save new
            $path = $request->file('images')[0]->store('additional_rooms', 'public');
            $room->image_path = $this->formatImageUrl($path);
        }

        $room->save();

        return response()->json([
            'message' => 'Room updated successfully',
            'room' => $room,
        ]);
    }

    /**
     * 🗑 Additional Room — delete
     */
    public function destroyAdditionalRoom($id)
    {
        $room = AdditionalRoomDetail::findOrFail($id);

        Storage::disk('public')->delete(str_replace('/storage/', '', $room->image_path));

        $room->delete();

        return response()->json(['message' => 'Additional room deleted']);
    }

    public function getAdditionalRooms(Property $property)
    {
        return response()->json($property->additionalRooms()->get());
    }

    /**
     * INTERNAL HELPERS: Format return output
     */
    private function formatPropertyResponse(Property $property)
    {
        // Fix property image array
        if ($property->images) {
            $imgs = json_decode($property->images, true);
            $property->images = array_map(fn($i) => $this->formatImageUrl($i), $imgs);
        }

        // Fix additional room image URLs
        foreach ($property->additionalRooms as $extra) {
            $extra->image_path = $this->formatImageUrl($extra->image_path);
        }

        // Fix room images
        foreach ($property->rooms as $room) {
            foreach ($room->images as $img) {
                $img->image_path = $this->formatImageUrl($img->image_path);
            }
        }

        return $property;
    }

    private function formatManyProperties($properties)
    {
        return $properties->map(fn($p) => $this->formatPropertyResponse($p));
    }
}

