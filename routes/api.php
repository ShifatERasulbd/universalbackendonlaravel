<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\LandingBannerController;
use App\Http\Controllers\InstallerController;

// Sanctum Login Route
Route::post('/admin/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user,
    ]);
});




// Sanctum Authenticated Route Example
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Installer
Route::get('/installer/business-categories', [InstallerController::class, 'getBusinessCategories']);
Route::middleware('web')->group(function () {
    Route::post('/installer/step-one', [InstallerController::class, 'storeStepOne']);
    Route::get('/installer/debug-step-one', [InstallerController::class, 'debugStepOne']);
    Route::get('/installer/themes', [InstallerController::class, 'getThemes']);
    Route::post('/installer/step-two', [InstallerController::class, 'storeStepTwo']);
    Route::get('/installer/step-three', [InstallerController::class, 'getStepThreeData']);
    Route::post('/installer/step-three', [InstallerController::class, 'storeStepThree']);
});

