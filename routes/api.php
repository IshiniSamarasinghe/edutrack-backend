<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes (stateless)
|--------------------------------------------------------------------------
| Keep only truly stateless/public APIs here.
| All cookie/session SPA routes (students + admin) are defined in web.php.
| This avoids duplicate paths and 401s from the api middleware.
|--------------------------------------------------------------------------
*/

// Example health endpoint (optional)
Route::get('/health', function () {
    return response()->json(['ok' => true, 'ts' => now()->toISOString()]);
});

// If you need token-based APIs in the future, put them here with auth:sanctum
// BUT do not duplicate any of the SPA endpoints that live in web.php.
// Route::middleware('auth:sanctum')->get('/v1/something', ...);
