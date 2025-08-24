<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();   // e.g., SWST 44062
            $table->string('title');            // e.g., Enterprise App Dev
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('credits')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('modules');
    }
};
