<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('i_c_t_courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('schedule_id')->constrained('i_c_t_course_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
