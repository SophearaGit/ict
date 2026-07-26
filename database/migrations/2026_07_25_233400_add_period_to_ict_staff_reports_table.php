<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * NOTE: verify this table name against your actual migration/model —
     * ICTStaffReport doesn't declare a $table property in what I've seen, so
     * this assumes Laravel's default snake_case conversion. Adjust if your
     * model overrides it.
     */
    private string $table = 'i_c_t_staff_reports';

    public function up(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            // The reporting period this report covers — separate from
            // created_at, which stays as pure "when was this row inserted"
            // audit metadata. Nullable so existing rows aren't broken;
            // backfill them in a follow-up step if you want historical
            // reports to have a period too.
            $table->date('period_start')->nullable()->after('report_content');
            $table->date('period_end')->nullable()->after('period_start');
        });
    }

    public function down(): void
    {
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn(['period_start', 'period_end']);
        });
    }
};
