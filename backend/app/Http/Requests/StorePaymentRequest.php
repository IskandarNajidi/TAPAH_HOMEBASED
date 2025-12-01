<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * 🔧 Convert numeric string values to real numbers
     * before validation runs.
     */
    protected function prepareForValidation(): void
    {
        foreach (['amount', 'deposit', 'additional_bill'] as $field) {
            if ($this->has($field) && $this->$field !== null && $this->$field !== '') {
                $this->merge([
                    $field => (float) $this->$field,
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            // 🔗 Relationships
            'property_id' => 'required|exists:properties,id',
            'room_id' => 'nullable|exists:rooms,id',

            // 💳 Payment Details
            'payment_type' => 'required|string|max:100',

            // 💵 Numeric Fields
            'amount' => 'required|numeric|min:0',
            'deposit' => 'required|numeric|min:0',
            'utility' => 'nullable|string|max:50',
            'additional_bill' => 'nullable|numeric|min:0',

            // 🧾 Metadata
            'payment_method' => 'nullable|string|max:100',
            'status' => 'nullable|in:pending,paid,failed',
            'reference' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
            'paid_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The payment amount must be a valid number.',
            'deposit.required' => 'The deposit amount is required.',
            'deposit.numeric' => 'The deposit must be a valid number.',
            'property_id.exists' => 'The selected property does not exist.',
            'room_id.exists' => 'The selected room does not exist.',
        ];
    }
}
