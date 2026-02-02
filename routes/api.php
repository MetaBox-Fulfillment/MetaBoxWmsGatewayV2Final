<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\MobileAuthController;
use App\Http\Controllers\Gateway\ProxyController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// Web auth (session alapú)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('store', [UserController::class, 'store']);
        Route::post('{user}/pin', [UserController::class, 'setPin']);
        Route::delete('{user}/pin', [UserController::class, 'removePin']);
    });
});

// Mobile auth (token alapú, PIN kóddal)
Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [MobileAuthController::class, 'me']);
        Route::post('/logout', [MobileAuthController::class, 'logout']);
    });
});

//MS
Route::any('{any}', [ProxyController::class, 'handle'])
    ->where('any', '.*');
    //->middleware('auth:sanctum');

