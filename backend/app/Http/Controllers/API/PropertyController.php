<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\AdditionalRoomDetail; // ✅ ADD THIS LINE
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
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

        unset($data['room_overview'], $data['price']);
        $rooms = $data['rooms'] ?? [];
        $additionalRooms = $data['additional_rooms'] ?? [];
        unset($data['rooms'], $data['additional_rooms']);

        DB::beginTransaction();

        try {
            $property = Property::create($data);

            // ✅ Upload property images
            if ($request->hasFile('images') && count($request->file('images')) > 0) {
                $uploaded = [];
                foreach ($request->file('images') as $file) {
                    $path = $file->store('property_images', 'public');
                    $uploaded[] = str_replace('\\', '/', Storage::url($path));
                }
                $property->images = json_encode($uploaded, JSON_UNESCAPED_SLASHES);
                $property->save();
            }

            // ✅ Create linked rooms
            foreach ($rooms as $roomData) {
                $room = $property->rooms()->create([
                    'room_type' => $roomData['room_type'] ?? null,
                    'capacity' => $roomData['capacity'] ?? null,
                    'current_occupancy' => $roomData['current_occupancy'] ?? null,
                    'bathroom_type' => $roomData['bathroom_type'] ?? null,
                    'bed_type' => $roomData['bed_type'] ?? null,
                ]);

                // Room images
                if (!empty($roomData['room_images']) && is_array($roomData['room_images'])) {
                    foreach ($roomData['room_images'] as $file) {
                        $path = $file->store('room_images/room', 'public');
                        RoomImage::create([
                            'room_id' => $room->id,
                            'type' => 'room',
                            'image_path' => str_replace('\\', '/', Storage::url($path)),
                        ]);
                    }
                }

                // Bathroom images
                if (!empty($roomData['bathroom_images']) && is_array($roomData['bathroom_images'])) {
                    foreach ($roomData['bathroom_images'] as $file) {
                        $path = $file->store('room_images/bathroom', 'public');
                        RoomImage::create([
                            'room_id' => $room->id,
                            'type' => 'bathroom',
                            'image_path' => str_replace('\\', '/', Storage::url($path)),
                        ]);
                    }
                }
            }

            // ✅ Additional rooms
            foreach ($additionalRooms as $extraRoom) {
                if (!empty($extraRoom['name']) && !empty($extraRoom['images']) && is_array($extraRoom['images'])) {
                    foreach ($extraRoom['images'] as $file) {
                        $path = $file->store('additional_rooms', 'public');
                        $property->additionalRooms()->create([
                            'name' => $extraRoom['name'],
                            'image_path' => str_replace('\\', '/', Storage::url($path)),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Property created successfully',
                'property' => $property->load(['rooms.images', 'additionalRooms']),
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
     * ✏️ Update existing property details safely
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

            $oldRooms = $property->available_rooms;
            $newRooms = $validated['available_rooms'] ?? $oldRooms;

            $property->update($validated);

            // ✅ Only sync rooms if explicitly changed
            if ($request->has('available_rooms') && $newRooms != $oldRooms) {
                $currentRoomCount = $property->rooms()->count();

                if ($newRooms > $currentRoomCount) {
                    // Add new blank rooms
                    $roomsToAdd = $newRooms - $currentRoomCount;
                    for ($i = 0; $i < $roomsToAdd; $i++) {
                        $property->rooms()->create([
                            'room_type' => null,
                            'capacity' => 0,
                            'current_occupancy' => 0,
                            'bathroom_type' => null,
                            'bed_type' => null,
                        ]);
                    }
                } elseif ($newRooms < $currentRoomCount) {
                    // Remove extra rooms (delete latest ones)
                    $roomsToRemove = $currentRoomCount - $newRooms;
                    $rooms = $property->rooms()->latest()->take($roomsToRemove)->get();

                    foreach ($rooms as $room) {
                        foreach ($room->images as $img) {
                            $path = str_replace('/storage/', '', $img->image_path);
                            Storage::disk('public')->delete($path);
                            $img->delete();
                        }
                        $room->delete();
                    }
                }
            }

            // ✅ Only replace property images if new ones were uploaded
            if ($request->hasFile('images') && count($request->file('images')) > 0) {
                if ($property->images) {
                    $oldImages = json_decode($property->images, true);
                    foreach ($oldImages as $img) {
                        $path = str_replace('/storage/', '', $img);
                        Storage::disk('public')->delete($path);
                    }
                }

                $uploaded = [];
                foreach ($request->file('images') as $file) {
                    $path = $file->store('property_images', 'public');
                    $uploaded[] = str_replace('\\', '/', Storage::url($path));
                }

                $property->images = json_encode($uploaded, JSON_UNESCAPED_SLASHES);
                $property->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Property updated successfully',
                'property' => $property->load(['rooms.images', 'additionalRooms']),
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

        return response()->json($properties);
    }

    /**
     * 🔍 Show one property
     */
    public function show($id)
    {
        $property = Property::with(['user', 'rooms.images', 'additionalRooms'])
            ->findOrFail($id);

        return response()->json($property);
    }

    /**
     * ❌ Delete property and linked data
     */
    public function destroy($id)
    {
        $property = Property::with(['rooms.images', 'additionalRooms'])->findOrFail($id);

        // Delete property images
        if ($property->images) {
            $images = json_decode($property->images, true);
            foreach ($images as $img) {
                $path = str_replace('/storage/', '', $img);
                Storage::disk('public')->delete($path);
            }
        }

        // Delete additional rooms
        foreach ($property->additionalRooms as $extra) {
            $path = str_replace('/storage/', '', $extra->image_path);
            Storage::disk('public')->delete($path);
            $extra->delete();
        }

        // Delete rooms and their images
        foreach ($property->rooms as $room) {
            foreach ($room->images as $img) {
                $path = str_replace('/storage/', '', $img->image_path);
                Storage::disk('public')->delete($path);
                $img->delete();
            }
            $room->delete();
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }

    /**
     * 🧩 Add custom "Additional Room Details" (Kitchen, Dining Room)
     */
    public function storeAdditionalRooms(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        try {
            if (!$request->hasFile('images')) {
                return response()->json(['message' => 'No image files found in request'], 400);
            }

            foreach ($request->file('images') as $file) {
                $path = $file->store('additional_rooms', 'public');
                $property->additionalRooms()->create([
                    'name' => $request->name,
                    'image_path' => str_replace('\\', '/', Storage::url($path)),
                ]);
            }

            return response()->json([
                'message' => 'Additional room added successfully',
                'additional_rooms' => $property->additionalRooms()->get(),
            ], 201);

        } catch (\Throwable $e) {
            \Log::error('❌ Failed to store additional rooms', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to add additional room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✏️ Update an existing additional room
     */
    public function updateAdditionalRoom(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            ]);

            // 🧩 Get the existing record
            $room = \App\Models\AdditionalRoomDetail::find($id);
            if (!$room) {
                return response()->json([
                    'message' => "Additional room with ID {$id} not found.",
                ], 404);
            }

            // ✅ Update name (optional)
            if (!empty($validated['name'])) {
                $room->name = $validated['name'];
            }

            // ✅ Replace image if new one uploaded
            if ($request->hasFile('images')) {
                // Delete old file safely
                if ($room->image_path && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image_path))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $room->image_path));
                }

                // Save new image (only use first uploaded file)
                $file = $request->file('images')[0];
                $path = $file->store('additional_rooms', 'public');
                $room->image_path = str_replace('\\', '/', Storage::url($path));
            }

            $room->save();

            return response()->json([
                'message' => '✅ Additional room updated successfully.',
                'room' => $room,
            ]);

        } catch (\Throwable $e) {
            \Log::error('❌ Failed to update additional room', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => '❌ Failed to update additional room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🗑 Delete an additional room
     */
    public function destroyAdditionalRoom($id)
    {
        try {
            $room = AdditionalRoomDetail::findOrFail($id);

            if ($room->image_path && Storage::disk('public')->exists(str_replace('/storage/', '', $room->image_path))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $room->image_path));
            }

            $room->delete();

            return response()->json(['message' => 'Additional room deleted successfully'], 200);
        } catch (\Throwable $e) {
            \Log::error('❌ Failed to delete additional room', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to delete additional room',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAdditionalRooms(Property $property)
    {
        return response()->json($property->additionalRooms()->get());
    }




}
