<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            // Link to your module offering row (what you list in /api/courses)
            $table->foreignId('module_offering_id')->constrained()->cascadeOnDelete();

            $table->text('overview')->nullable();
            $table->json('topics')->nullable();       // ["Intro", "HTTP", ...]
            $table->json('assessments')->nullable();  // [{"name":"Assignment","weight":30}, ...]
            $table->json('resources')->nullable();    // [{"label":"Syllabus","url":"..."}]
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('course_contents');
    }
};
