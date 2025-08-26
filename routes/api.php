<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\MeController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses', [CourseCatalogController::class, 'index']);
    Route::post('/enrollments', [EnrollmentController::class, 'store']);
    Route::get('/my-courses', [EnrollmentController::class, 'index']);
    Route::get('/results', [ResultController::class, 'index']);

    Route::get('/me', function (Request $request) {
        // returns: id, name, email, index_number, avatar_url
        return $request->user()->only(['id','name','email','index_number','avatar_url']);
    });

    Route::post('/me/avatar', [MeController::class, 'uploadAvatar']);

    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

});
