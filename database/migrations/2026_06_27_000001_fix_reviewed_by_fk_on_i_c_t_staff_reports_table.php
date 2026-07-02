<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->dropForeign(['reviewed_by']);
        });

        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->foreign('reviewed_by')->references('id')->on('admins')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->dropForeign(['reviewed_by']);
        });

        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
        });
    }
};
