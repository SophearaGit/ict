<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->foreignId('reviewed_by')->nullable()->after('report_content')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('status');
            $table->softDeletes();
        });

        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_staff_reports', function (Blueprint $table): void {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['reviewed_by', 'reviewed_at', 'deleted_at']);
            $table->dropIndex(['status']);
        });
    }
};
