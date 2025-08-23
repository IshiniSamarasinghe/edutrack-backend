<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 

return new class extends Migration
{
    /**
     * Run the migrations.
     */

public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'index_number')) {
            $table->string('index_number')->nullable()->after('email_verified_at');
        }
        if (!Schema::hasColumn('users', 'avatar_path')) {
            $table->string('avatar_path')->nullable()->after('index_number');
        }
    });
}
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'avatar_path')) {
            $table->dropColumn('avatar_path');
        }
        if (Schema::hasColumn('users', 'index_number')) {
            $table->dropColumn('index_number');
        }
    });
}

};
