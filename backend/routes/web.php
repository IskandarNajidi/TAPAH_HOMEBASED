<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Exception $e) {
        Log::error('Google login failed: ' . $e->getMessage());
        return redirect()->away('https://tapah-homebased.vercel.app/login?error=google_failed');
    }

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'role' => 'tenant',
            'password' => bcrypt(Str::random(16)),
        ]
    );

    $user->update(['last_login_at' => now(), 'last_logout_at' => null]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return redirect()->away("https://tapah-homebased.vercel.app/auth/google/callback?token={$token}");
});

Route::middleware('auth:sanctum')->post('/auth/google/logout', function () {
    $user = Auth::user();
    if ($user) {
        $user->update(['last_logout_at' => now()]);
        $user->currentAccessToken()->delete();
    }
    return response()->json(['message' => 'Logged out successfully']);
});

Route::get('/', function () {
    return view('welcome');
});
