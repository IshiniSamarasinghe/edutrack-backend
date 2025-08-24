<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\EnrollmentController;

Route::middleware('auth:sanctum')->group(function () {
    // Course catalog (filters by logged-in user's type + pathway; optional ?level=3|4)
    Route::get('/courses', [CourseCatalogController::class, 'index']);

    // Enrollments
    Route::post('/enrollments', [EnrollmentController::class, 'store']); // enroll by code
    Route::get('/my-courses', [EnrollmentController::class, 'index']);   // list enrolled courses
});
