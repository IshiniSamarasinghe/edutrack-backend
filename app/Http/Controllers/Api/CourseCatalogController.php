<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseCatalogController extends Controller
{
    // GET /api/courses?level=3|4  (auth:sanctum)
    public function index(Request $request)
    {
        $user  = $request->user();
        $level = (int) $request->query('level');

        $q = DB::table('module_offerings')
            ->join('modules', 'module_offerings.module_id', '=', 'modules.id')
            // ✅ show enrollment status per course for this user
            ->leftJoin('enrollments', function ($join) use ($user) {
                $join->on('enrollments.module_offering_id', '=', 'module_offerings.id')
                     ->where('enrollments.user_id', '=', $user->id);
            })
            ->where('module_offerings.type', $user->type)
            ->where('module_offerings.pathway', $user->pathway)
            ->whereIn('module_offerings.year', [3, 4]);

        if (in_array($level, [3, 4], true)) {
            $q->where('module_offerings.year', $level);
        }

        $rows = $q->orderBy('module_offerings.year')
            ->orderBy('module_offerings.semester')
            ->orderByRaw('LOWER(modules.code)')
            ->get([
                'module_offerings.id',
                'modules.code',
                'modules.title',
                'modules.description',
                'module_offerings.year',
                'module_offerings.semester',
                'enrollments.id as enrollment_id', // null if not enrolled
            ]);

        // shape for frontend
        $items = $rows->map(function ($r) {
            return [
                'id'          => $r->id,          // offering id
                'code'        => $r->code,
                'title'       => $r->title,
                'description' => $r->description,
                'year'        => $r->year,
                'semester'    => $r->semester,
                'enrolled'    => !is_null($r->enrollment_id),
            ];
        });

        return response()->json([
            'filters' => [
                'type'    => $user->type,
                'pathway' => $user->pathway,
                'level'   => $level ?: null,
            ],
            'items' => $items,
        ]);
    }
}
