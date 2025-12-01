<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // 🏘️ Room Details
            'property_id' => 'sometimes|exists:properties,id',
            'room_type' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:0',
            'current_occupancy' => 'nullable|integer|min:0',
            'bathroom_type' => 'nullable|string|max:100',   // 🟢 now flexible
            'bed_type' => 'nullable|string|max:100',   // 🟢 now flexible

            // 🖼️ Optional image uploads
            'room_images' => 'nullable|array',
            'room_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',

            'bathroom_images' => 'nullable|array',
            'bathroom_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',

            'other_room_images' => 'nullable|array',
            'other_room_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'property_id.exists' => 'Invalid property selected.',
            'room_images.*.image' => 'Each uploaded room image must be a valid image file.',
            'bathroom_images.*.image' => 'Each uploaded bathroom image must be a valid image file.',
            'other_room_images.*.image' => 'Each uploaded image must be a valid image file.',
        ];
    }
}
