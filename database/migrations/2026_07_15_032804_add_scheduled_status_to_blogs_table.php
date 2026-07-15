<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            // MySQL doesn't let you just add an enum value directly, so raw statement:
            DB::statement("ALTER TABLE blogs MODIFY COLUMN status ENUM('draft','scheduled','published') DEFAULT 'draft'");
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            DB::statement("ALTER TABLE blogs MODIFY COLUMN status ENUM('draft','published') DEFAULT 'draft'");
        });
    }
};
