<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void {
    Schema::create('achievements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('link')->nullable();
        $table->date('date')->nullable();
        $table->timestamps();
    });

    Schema::create('achievement_media', function (Blueprint $table) {
        $table->id();
        $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
        $table->string('path');
        $table->string('mime', 64)->nullable();
        $table->unsignedInteger('size')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements_tables');
    }
};
