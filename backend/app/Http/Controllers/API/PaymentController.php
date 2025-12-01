<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 🧾 Store a new payment
    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $payment = Payment::create($data);

        return response()->json([
            'message' => 'Payment recorded successfully',
            'payment' => $payment->load(['user', 'property', 'room'])
        ], 201);
    }

    // 📜 List all payments
    public function index()
    {
        $payments = Payment::with(['user', 'property', 'room'])->latest()->get();
        return response()->json($payments);
    }

    // 🔍 Show specific payment
    public function show($id)
    {
        $payment = Payment::with(['user', 'property', 'room'])->findOrFail($id);
        return response()->json($payment);
    }

    // ✏️ Update payment (expanded to include landlord-editable fields)
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'payment_type' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'deposit' => 'sometimes|numeric|min:0',
            'utility' => 'sometimes|string|max:255',
            'additional_bill' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:1000',
            'status' => 'sometimes|string|max:50',
        ]);

        $payment->update($validated);

        return response()->json([
            'message' => 'Payment updated successfully',
            'payment' => $payment->refresh()
        ]);
    }

    // 🗑️ Delete payment
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
