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
        Schema::table('intern_reports', function (Blueprint $table) {
            $table->date('period_start')->nullable()->after('reported_by');
            $table->date('period_end')->nullable()->after('period_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intern_reports', function (Blueprint $table) {
            $table->dropColumn([
                'period_start',
                'period_end',
            ]);
        });
    }
};
