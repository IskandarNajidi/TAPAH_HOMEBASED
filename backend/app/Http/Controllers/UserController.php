<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'resident' => 'required|string|max:255',
            'sex' => 'required|in:male,female,other',
        ]);

        // ✅ Block editing Google users' email and role
        if ($user->google_id) {
            unset($validated['email']);
        }

        $user->update($validated);

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // revoke all tokens
        $user->delete(); // delete account
        return response()->json(['message' => 'Account deleted successfully']);
    }
}
