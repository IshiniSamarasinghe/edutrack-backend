<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleOffering;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCourseController extends Controller
{
    // Infer credits from the last digit of the code (e.g., AINT 31012 -> 2).
    private function inferCreditsFromCode(?string $code): ?int
    {
        if (!$code) return null;
        if (preg_match('/(\d)\s*$/', $code, $m)) {
            return (int) $m[1];
        }
        return null;
    }

    /**
     * GET /api/admin/courses
     * Supports: q, page, per_page, sort, dir
     * - per_page=all -> returns all courses (no pagination)
     */
    public function index(Request $request)
    {
        $q       = trim((string) $request->query('q', ''));
        $sort    = $request->query('sort', 'code');   // code|title|credits|created_at|updated_at
        $dir     = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $perPage = $request->query('per_page', 50);

        // Base query:
        // - include soft-deleted
        // - eager load offerings with enrollments_count so we can sum them
        $query = Module::withTrashed()
            ->with(['offerings' => function ($q2) {
                $q2->select('id', 'module_id', 'type', 'pathway', 'year', 'semester')
                   ->withCount('enrollments'); // expects ModuleOffering::enrollments()
            }]);

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%{$q}%")
                  ->orWhere('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Whitelist sorting
        $allowedSorts = ['code', 'title', 'credits', 'created_at', 'updated_at'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'code';
        }
        $query->orderBy($sort, $dir);

        // Return ALL (no pagination)
        if ($perPage === 'all') {
            $items = $query->get();

            $data = $items->map(function ($m) {
                $credits  = $m->credits ?? $this->inferCreditsFromCode($m->code);
                $enrolled = (int) $m->offerings->sum('enrollments_count');

                return [
                    'id'          => $m->id,
                    'code'        => $m->code,
                    'title'       => $m->title,
                    'credits'     => $credits,
                    'description' => $m->description,
                    'status'      => $m->trashed() ? 'archived' : 'active',
                    'enrolled'    => $enrolled,
                    'offerings'   => $m->offerings->map(fn ($o) => [
                        'type'     => $o->type,
                        'pathway'  => $o->pathway,
                        'year'     => $o->year,
                        'semester' => $o->semester,
                    ])->values(),
                ];
            })->values();

            return response()->json([
                'data'      => $data,
                'total'     => $items->count(),
                'paginated' => false,
            ], 200);
        }

        // Paginated path (cap per_page for safety)
        $perPage = (int) $perPage;
        $perPage = $perPage > 0 ? min($perPage, 1000) : 50;

        $paginated = $query->paginate($perPage)->appends($request->query());

        $data = collect($paginated->items())->map(function ($m) {
            $credits  = $m->credits ?? $this->inferCreditsFromCode($m->code);
            $enrolled = (int) $m->offerings->sum('enrollments_count');

            return [
                'id'          => $m->id,
                'code'        => $m->code,
                'title'       => $m->title,
                'credits'     => $credits,
                'description' => $m->description,
                'status'      => $m->trashed() ? 'archived' : 'active',
                'enrolled'    => $enrolled,
                'offerings'   => $m->offerings->map(fn ($o) => [
                    'type'     => $o->type,
                    'pathway'  => $o->pathway,
                    'year'     => $o->year,
                    'semester' => $o->semester,
                ])->values(),
            ];
        })->values();

        return response()->json([
            'data'         => $data,
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'paginated'    => true,
        ], 200);
    }

    /**
     * POST /api/admin/courses
     * Optionally accepts year/semester to create an initial offering.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => ['required', 'string', 'max:50', 'unique:modules,code'],
            'title'       => ['required', 'string', 'max:255'],
            'credits'     => ['nullable', 'integer', 'min:0', 'max:60'],
            'description' => ['nullable', 'string'],
            'year'        => ['nullable', 'integer', 'min:1', 'max:10'],
            'semester'    => ['nullable', 'integer', 'min:1', 'max:10'],
            'type'        => ['nullable', 'string', 'max:10'],
            'pathway'     => ['nullable', 'string', 'max:100'],
        ]);

        $course = Module::create([
            'code'        => $data['code'],
            'title'       => $data['title'],
            'credits'     => $data['credits'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['year']) && !empty($data['semester'])) {
            ModuleOffering::create([
                'module_id' => $course->id,
                'type'      => $data['type']     ?? 'CT',
                'pathway'   => $data['pathway']  ?? 'software_systems',
                'year'      => $data['year'],
                'semester'  => $data['semester'],
            ]);
        }

        // reload offerings with count for consistent response shape
        $course->load(['offerings' => function ($q2) {
            $q2->withCount('enrollments');
        }]);

        return response()->json([
            'message' => 'Course created',
            'data'    => [
                'id'          => $course->id,
                'code'        => $course->code,
                'title'       => $course->title,
                'credits'     => $course->credits ?? $this->inferCreditsFromCode($course->code),
                'description' => $course->description,
                'status'      => 'active',
                'enrolled'    => (int) $course->offerings->sum('enrollments_count'),
                'offerings'   => $course->offerings->map(fn ($o) => [
                    'type'     => $o->type,
                    'pathway'  => $o->pathway,
                    'year'     => $o->year,
                    'semester' => $o->semester,
                ])->values(),
            ],
        ], 201);
    }

    /**
     * PUT /api/admin/courses/{course}
     */
    public function update(Request $request, Module $course)
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'credits'     => ['nullable', 'integer', 'min:0', 'max:60'],
            'description' => ['nullable', 'string'],
            // 'code'     => ['required','string','max:50', Rule::unique('modules','code')->ignore($course->id)],
        ]);

        $course->update($data);

        $course->load(['offerings' => function ($q2) {
            $q2->withCount('enrollments');
        }]);

        return response()->json([
            'message' => 'Course updated',
            'data'    => [
                'id'          => $course->id,
                'code'        => $course->code,
                'title'       => $course->title,
                'credits'     => $course->credits ?? $this->inferCreditsFromCode($course->code),
                'description' => $course->description,
                'status'      => $course->trashed() ? 'archived' : 'active',
                'enrolled'    => (int) $course->offerings->sum('enrollments_count'),
                'offerings'   => $course->offerings->map(fn ($o) => [
                    'type'     => $o->type,
                    'pathway'  => $o->pathway,
                    'year'     => $o->year,
                    'semester' => $o->semester,
                ])->values(),
            ],
        ], 200);
    }

    /**
     * DELETE /api/admin/courses/{course}
     * Soft-delete (archive).
     */
    public function destroy(Module $course)
    {
        $course->delete();
        return response()->json(['message' => 'Course archived'], 200);
    }

    /**
     * POST /api/admin/courses/{id}/restore
     */
    public function restore(int $id)
    {
        $course = Module::withTrashed()->findOrFail($id);
        $course->restore();

        return response()->json(['message' => 'Course restored'], 200);
    }
}
