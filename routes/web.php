<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FallbackCsrfCookieController;
use App\Http\Controllers\Admin\ResultUploadController;
use App\Http\Controllers\Api\ResultController as ApiResultController;

/*
| Sanctum CSRF cookie (works whether Sanctum's controller exists or not)
*/
if (class_exists(\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class)) {
    Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
} else {
    Route::get('/sanctum/csrf-cookie', [FallbackCsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
}

/*
| Registration (use your controller)
*/
Route::post('/register', [AuthController::class, 'register']);

/*
| Session login/logout for SPA (Sanctum uses the web session)
*/
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (! Auth::attempt($credentials, $request->boolean('remember'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $request->session()->regenerate(); // 🔑 prevent session fixation
    return response()->noContent();    // 204
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->noContent();    // 204
});

/*
| Convenience endpoint to fetch the logged-in user via session
| (use this in the frontend if you prefer /user instead of /api/me)
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

/*
| Admin: results upload
*/
Route::get('/admin/results/upload', [ResultUploadController::class, 'form']);
Route::post('/admin/results/upload', [ResultUploadController::class, 'upload']);
Route::middleware('auth')->get('/api/results', [ApiResultController::class, 'index']);
