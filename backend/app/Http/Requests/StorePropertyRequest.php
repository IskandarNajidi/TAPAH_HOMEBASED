<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * ✅ Decode JSON arrays sent via FormData before validation
     */
    protected function prepareForValidation()
    {
        foreach (['amenities', 'rooms', 'additional_rooms'] as $jsonField) {
            if ($this->has($jsonField) && is_string($this->$jsonField)) {
                $decoded = json_decode($this->$jsonField, true);

                // Fallback to empty array if JSON invalid
                $this->merge([
                    $jsonField => is_array($decoded) ? $decoded : [],
                ]);
            }
        }
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            // 🧾 Basic Info
            'title' => $isUpdate ? 'sometimes|string|max:255' : 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'area_name' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:255',
            'total_units' => 'nullable|integer|min:0',
            'available_rooms' => 'nullable|integer|min:0',
            'tenants_needed' => 'nullable|integer|min:0',
            'current_tenants' => 'nullable|integer|min:0',
            'contract_duration' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:50',
            'furnished_level' => 'nullable|string|max:100',
            'total_bathrooms' => 'nullable|integer|min:0',

            // 🖼️ Property Images (optional)
            'images' => $isUpdate ? 'sometimes|nullable|array' : 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',

            // 🧰 Amenities (flexible text)
            'amenities' => 'nullable|array',
            'amenities.*' => 'nullable|string|max:100',

            // 📍 Location Details (flexible text)
            'distance_ui_tm' => 'nullable|string|max:255',
            'nearby_shop' => 'nullable|string|max:255',
            'nearby_facility' => 'nullable|string|max:255',
            'location_advantages' => 'nullable|string|max:500',

            // 🏘️ Room Details (now fully flexible)
            'rooms' => 'nullable|array',
            'rooms.*.room_type' => 'nullable|string|max:100',
            'rooms.*.capacity' => 'nullable|integer|min:0',
            'rooms.*.current_occupancy' => 'nullable|integer|min:0',
            'rooms.*.bathroom_type' => 'nullable|string|max:100',     // 🟢 flexible now
            'rooms.*.bed_type' => 'nullable|string|max:100',          // 🟢 flexible now
            'rooms.*.furnished_level' => 'nullable|string|max:100',   // 🟢 flexible now
            'rooms.*.gender_preference' => 'nullable|string|max:50',  // 🟢 flexible now
            'rooms.*.rental_mode' => 'nullable|string|max:100',       // 🟢 flexible now
            'rooms.*.monthly_rent' => 'nullable|numeric|min:0',
            'rooms.*.deposit' => 'nullable|numeric|min:0',

            // 🧩 Additional Room Details (flexible)
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*.name' => 'nullable|string|max:255',
            'additional_rooms.*.images' => 'nullable|array',
            'additional_rooms.*.images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    /**
     * 🗒 Custom messages for clarity
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The property title is required.',
            'images.*.image' => 'Each uploaded file must be a valid image.',
            'images.*.mimes' => 'Images must be in JPG, JPEG, PNG, or WEBP format.',
            'images.*.max' => 'Each image must not exceed 4MB.',
        ];
    }
}
