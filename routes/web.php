<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackCsrfCookieController;

if (class_exists(\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class)) {
    Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
} else {
    Route::get('/sanctum/csrf-cookie', [FallbackCsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
}

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/user',      [AuthController::class, 'user']);
Route::post('/logout',   [AuthController::class, 'logout']);
