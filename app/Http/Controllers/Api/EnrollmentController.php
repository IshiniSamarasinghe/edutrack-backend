<?php

// app/Http/Controllers/Api/EnrollmentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\ModuleOffering;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EnrollmentController extends Controller
{
    // POST /api/enrollments  { offering_id, code }
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'offering_id' => ['required','integer','exists:module_offerings,id'],
            'code'        => ['required','string','max:40'], // user types SWST41022
        ]);

        $offering = ModuleOffering::with('module')->findOrFail($data['offering_id']);

        // Ensure this offering is for THIS user's type & pathway
        if ($offering->type !== $user->type || $offering->pathway !== $user->pathway) {
            return response()->json(['message' => 'This course is not available to your pathway.'], Response::HTTP_FORBIDDEN);
        }

        // Compare typed code to module code (normalize: remove spaces, uppercase)
        $typed   = strtoupper(preg_replace('/\s+/', '', $data['code']));
        $actual  = strtoupper(preg_replace('/\s+/', '', $offering->module->code));
        if ($typed !== $actual) {
            throw ValidationException::withMessages(['code' => ['Invalid enrollment key (course code).']]);
        }

        // Prevent duplicates
        $exists = Enrollment::where('user_id', $user->id)
            ->where('module_offering_id', $offering->id)
            ->exists();
        if ($exists) {
            return response()->json(['message' => 'Already enrolled in this course.'], Response::HTTP_CONFLICT);
        }

        $enrollment = Enrollment::create([
            'user_id'            => $user->id,
            'module_offering_id' => $offering->id,
        ]);

        return response()->json(['enrollment' => $enrollment], Response::HTTP_CREATED);
    }

    // GET /api/my-courses
    public function index(Request $request)
    {
        $user = $request->user();

        $items = Enrollment::with(['offering.module'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($e) {
                return [
                    'enrollment_id' => $e->id,
                    'code'          => $e->offering->module->code,
                    'title'         => $e->offering->module->title,
                    'description'   => $e->offering->module->description,
                    'year'          => $e->offering->year,
                    'semester'      => $e->offering->semester,
                    'enrolled_at'   => $e->created_at,
                ];
            });

        return response()->json(['items' => $items]);
    }
public function destroy(Request $request, Enrollment $enrollment)
    {
        // only allow the owner to delete their enrollment
        abort_if($enrollment->user_id !== $request->user()->id, 403, 'Forbidden');

        $enrollment->delete();
        return response()->noContent(); // 204
    }
}
