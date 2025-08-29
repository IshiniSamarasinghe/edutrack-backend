<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/**
 * Student APIs
 */
use App\Http\Controllers\Api\CourseCatalogController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\AchievementController;

/**
 * Admin APIs
 */
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminEnrollmentStatsController;

/**
 * Student SPA Auth controller (login/register/logout)
 */
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| PUBLIC admin auth (must run through "web" to create session)
|--------------------------------------------------------------------------
*/
Route::middleware('web')->prefix('api/admin/auth')->group(function () {
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login',    [AdminAuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED admin routes (need both web + auth:admin to read session)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth:admin'])
    ->prefix('api/admin')
    ->group(function () {
        // admin session APIs
        Route::post('auth/logout', [AdminAuthController::class, 'logout']);
        Route::get('auth/me',      [AdminAuthController::class, 'me']);

        // admin profile
        Route::get('profile', [AdminProfileController::class, 'show']);
        Route::put('profile', [AdminProfileController::class, 'update']);

        // users management
        Route::get   ('users',       [AdminUserController::class, 'index']);
        Route::get   ('users/{id}',  [AdminUserController::class, 'show']);
        Route::post  ('users',       [AdminUserController::class, 'store']);
        Route::put   ('users/{id}',  [AdminUserController::class, 'update']);
        Route::delete('users/{id}',  [AdminUserController::class, 'destroy']);

        // admins table
        Route::get   ('admins',      [AdminsController::class, 'index']);
        Route::delete('admins/{id}', [AdminsController::class, 'destroy']);

        // courses
        Route::get   ('courses',              [AdminCourseController::class, 'index']);
        Route::post  ('courses',              [AdminCourseController::class, 'store']);
        Route::put   ('courses/{id}',         [AdminCourseController::class, 'update']);
        Route::delete('courses/{id}',         [AdminCourseController::class, 'destroy']);
        Route::post  ('courses/{id}/restore', [AdminCourseController::class, 'restore']);
    });

/*
|--------------------------------------------------------------------------
| Sanctum CSRF cookie (for SPA)
|--------------------------------------------------------------------------
*/
if (class_exists(\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class)) {
    Route::get('/sanctum/csrf-cookie', [\Laravel\Sanctum\Http\Controllers\CsrfCookieController::class, 'show']);
}

/*
|--------------------------------------------------------------------------
| STUDENT SPA AUTH (Sanctum cookies) -> /login, /register, /logout, /user, /api/me
|--------------------------------------------------------------------------
*/
Route::middleware('web')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/logout',   [AuthController::class, 'logout']);

    // session check used by the SPA
    Route::get('/user', function (Request $req) {
        $u = $req->user();
        return $u
            ? response()->json($u)
            : response()->json(['message' => 'Unauthenticated.'], 401);
    });

    // same info but under /api/me (some parts of the app call this)
    Route::get('/api/me', function (Request $req) {
        $u = $req->user();
        return $u
            ? response()->json(['data' => $u])
            : response()->json(['message' => 'Unauthenticated.'], 401);
    });
});

/*
|--------------------------------------------------------------------------
| STUDENT PUBLIC API (leave truly public things here)
|--------------------------------------------------------------------------
*/
Route::middleware('web')->prefix('api')->group(function () {
    // GET list (your controller uses the logged-in user; it will still work when logged in)
    Route::get('achievements', [AchievementController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| STUDENT PROTECTED API (requires login)
| Matches frontend calls:
|   GET /api/courses
|   GET /api/my-courses
|   GET /api/results
|   POST /api/enrollments
|   DELETE /api/enrollments/{enrollment}
|   POST /api/achievements
|   DELETE /api/achievements/{achievement}
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'auth'])->prefix('api')->group(function () {
    // Course catalog (your controller reads $request->user())
    Route::get('courses', [CourseCatalogController::class, 'index']);

    // Your EnrollmentController implements index() (not myCourses())
    Route::get('my-courses', [EnrollmentController::class, 'index']);

    // enroll/unenroll endpoints
    Route::post  ('enrollments',              [EnrollmentController::class, 'store']);
    Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy']);

    // results for the logged-in student
    Route::get('results', [ResultController::class, 'index']);

    // ✅ NEW: achievements write routes (fixes 405 on POST)
    Route::post  ('achievements',                    [AchievementController::class, 'store']);
    Route::delete('achievements/{achievement}',      [AchievementController::class, 'destroy']);
    // If you add update() later:
    // Route::put('achievements/{achievement}',      [AchievementController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| Admin stats: per-user enrollments count (admin-prefixed)
|--------------------------------------------------------------------------
*/
Route::middleware(['web','auth:admin'])->prefix('api/admin')->group(function () {
    Route::get('enrollments/user-counts', [AdminEnrollmentStatsController::class, 'userCounts']);
});

/*
|--------------------------------------------------------------------------
| (Kept) Your original bare route — unchanged
|--------------------------------------------------------------------------
*/
Route::get('enrollments/user-counts', [AdminEnrollmentStatsController::class, 'userCounts']);
