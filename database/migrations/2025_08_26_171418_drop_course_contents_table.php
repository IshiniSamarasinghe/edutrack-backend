<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop if it exists (works fine on SQLite/MySQL/Postgres)
        Schema::dropIfExists('course_contents');
    }

    public function down(): void
    {
        // Recreate a minimal version in case you ever rollback
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_offering_id')->constrained()->cascadeOnDelete();
            $table->text('overview')->nullable();
            $table->json('topics')->nullable();
            $table->timestamps();
        });
    }
};
