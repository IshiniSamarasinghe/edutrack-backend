<?php
// app/Http/Controllers/Admin/AdminEnrollmentStatsController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;

class AdminEnrollmentStatsController extends Controller
{
    public function userCounts()
    {
        // expecting Enrollment has user_id
        $rows = Enrollment::selectRaw('user_id, COUNT(*) as c')
            ->groupBy('user_id')->pluck('c', 'user_id');

        return response()->json(['data' => $rows]);
    }
}
