<?php

// app/Http/Controllers/Api/CourseContentController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CourseContentController extends Controller
{
    // GET /api/courses/{offering}/content
    public function show($offeringId)
    {
        // TODO: Replace with real data lookups
        return response()->json([
            'offering_id' => (int) $offeringId,
            'title'       => 'Sample syllabus',
            'sections'    => [
                ['title' => 'Week 1: Basics',   'items' => ['Intro', 'Lecture 1 slides']],
                ['title' => 'Week 2: Advanced', 'items' => ['Lab 1', 'Reading list']],
            ],
        ]);
    }
}
