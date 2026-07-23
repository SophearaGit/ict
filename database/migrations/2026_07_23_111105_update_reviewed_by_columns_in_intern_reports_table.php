<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('intern_reports', function (Blueprint $table): void {
            // Drop the old single-reviewer FK
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn('reviewed_by');

            $table->foreignId('reviewed_by_admin_id')
                ->nullable()
                ->after('status')
                ->constrained('admins')
                ->nullOnDelete();

            $table->foreignId('reviewed_by_staff_id')
                ->nullable()
                ->after('reviewed_by_admin_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('intern_reports', function (Blueprint $table): void {
            $table->dropForeign(['reviewed_by_admin_id']);
            $table->dropForeign(['reviewed_by_staff_id']);
            $table->dropColumn(['reviewed_by_admin_id', 'reviewed_by_staff_id']);

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }
};
