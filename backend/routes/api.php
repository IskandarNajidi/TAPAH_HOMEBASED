<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\PropertyController;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\PaymentController;

/*
|--------------------------------------------------------------------------
| 🌐 Google OAuth
|--------------------------------------------------------------------------
*/
// Route::get('/auth/google/redirect', function () {
//     return Socialite::driver('google')->stateless()->redirect();
// });

// Route::get('/auth/google/callback', function () {
//     $googleUser = Socialite::driver('google')->stateless()->user();

//     $user = User::updateOrCreate(
//         ['email' => $googleUser->getEmail()],
//         [
//             'name' => $googleUser->getName(),
//             'google_id' => $googleUser->getId(),
//             'avatar' => $googleUser->getAvatar(),
//             'password' => bcrypt(Str::random(16)),
//         ]
//     );

//     $token = $user->createToken('auth_token')->plainTextToken;

//     return response()->json([
//         'token' => $token,
//         'user' => $user,
//     ]);
// });

/*
|--------------------------------------------------------------------------
| 🧾 Public Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 🔒 Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // 👤 User Management
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::delete('/user/delete', [UserController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | 🏠 Property CRUD
    |--------------------------------------------------------------------------
    */
    Route::apiResource('properties', PropertyController::class);

    /*
    |--------------------------------------------------------------------------
    | 🏘 Room Management
    |--------------------------------------------------------------------------
    */
    Route::get('properties/{property}/rooms', [RoomController::class, 'index']);
    Route::post('properties/{property}/rooms', [RoomController::class, 'store']);
    Route::put('properties/{property}/rooms/{room}', [RoomController::class, 'update']);
    Route::delete('properties/{property}/rooms/{room}', [RoomController::class, 'destroy']);

    // ✅ Direct room delete (backup/fallback)
    Route::delete('rooms/{room}', [RoomController::class, 'forceDestroy']);

    /*
    |--------------------------------------------------------------------------
    | 🖼 Room Image Management
    |--------------------------------------------------------------------------
    */
    Route::post('/rooms/{room}/images', [RoomController::class, 'uploadImages']);
    Route::delete('/rooms/{room}/images/{image}', [RoomController::class, 'deleteImage']);

    /*
    |--------------------------------------------------------------------------
    | 💳 Payment Management
    |--------------------------------------------------------------------------
    */
    Route::apiResource('payments', PaymentController::class);

    /*
    |--------------------------------------------------------------------------
    | 🧩 Additional Room Details
    |--------------------------------------------------------------------------
    */
    Route::get('/properties/{property}/additional-rooms', [PropertyController::class, 'getAdditionalRooms']);
    Route::post('/properties/{property}/additional-rooms', [PropertyController::class, 'storeAdditionalRooms']);
    Route::put('/additional-rooms/{id}', [PropertyController::class, 'updateAdditionalRoom']);
    Route::delete('/additional-rooms/{id}', [PropertyController::class, 'destroyAdditionalRoom']);
});
