<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // add new columns
            $table->string('student_number')->nullable()->after('email');
            $table->string('type', 3)->nullable()->after('student_number'); // CT / ET / CS
            $table->string('pathway')->nullable()->after('type'); // store pathway slug/name for now
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_number','type','pathway']);
        });
    }
};

