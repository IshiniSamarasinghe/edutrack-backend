<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('module_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('type', 3)->index();            // CT | ET | CS
            $table->string('pathway')->index();            // e.g., software_systems
            $table->unsignedTinyInteger('year')->index();  // 3 or 4
            $table->unsignedTinyInteger('semester')->index(); // 1 or 2
            $table->timestamps();

            $table->unique(['module_id','type','pathway','year','semester'], 'uniq_offering');
        });
    }
    public function down(): void {
        Schema::dropIfExists('module_offerings');
    }
};

