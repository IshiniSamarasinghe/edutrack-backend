<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * GET /api/results
     * Returns:
     * {
     *   "gpa": 3.67 | null,
     *   "items": [ { academic_year, grade, grade_points, code, title, credits, level, semester }, ... ]
     * }
     */
    public function index(Request $req)
    {
        $user = $req->user();

        // Pull rows with safe fallbacks:
        // - credits: use 3 if null (adjust this default if you prefer)
        // - grade_points: use 0 if null (e.g., not graded yet)
        $rows = DB::table('results')
            ->join('module_offerings', 'module_offerings.id', '=', 'results.module_offering_id')
            ->join('modules', 'modules.id', '=', 'module_offerings.module_id')
            ->where('results.user_id', $user->id)
            ->select([
                'results.academic_year',
                'results.grade',
                DB::raw('COALESCE(results.grade_points, 0) as grade_points'),
                'modules.code',
                'modules.title',
                DB::raw('COALESCE(modules.credits, 3) as credits'),
                DB::raw('module_offerings.year as level'),
                'module_offerings.semester',
            ])
            ->orderBy('level')
            ->orderBy('semester')
            ->orderBy('modules.code')
            ->get();

        // Compute weighted GPA = sum(grade_points * credits) / sum(credits)
        $totalCredits = 0;
        $weightedSum  = 0.0;

        foreach ($rows as $r) {
            $c = (int) $r->credits;
            $gp = (float) $r->grade_points;

            // Only count rows that actually carry credit
            if ($c > 0) {
                $totalCredits += $c;
                $weightedSum  += $gp * $c;
            }
        }

        $gpa = $totalCredits > 0 ? round($weightedSum / $totalCredits, 2) : null;

        return response()->json([
            'gpa'   => $gpa,
            'items' => $rows,
        ]);
    }
}
