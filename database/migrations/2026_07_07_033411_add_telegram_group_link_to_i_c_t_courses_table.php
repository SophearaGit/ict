<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('i_c_t_courses', function (Blueprint $table) {
            $table->string('telegram_group_link')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_courses', function (Blueprint $table) {
            $table->dropColumn('telegram_group_link');
        });
    }
};
