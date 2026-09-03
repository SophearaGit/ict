<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Drops the 'late' status on student_attendances.status in favor of
 * 'permission' (an excused absence) — matching the `permission` column
 * that already exists on student_reports but never had a matching
 * attendance status feeding it.
 *
 * MySQL enum columns are widened/narrowed via raw MODIFY COLUMN
 * statements (same pattern as the payment_method / payment_option enum
 * migrations in this project). We widen first so both values are valid
 * at once, remap any existing 'late' rows to 'permission', then narrow
 * to drop 'late' — this avoids MySQL truncating unmigrated 'late' rows
 * to an invalid empty string during the narrowing ALTER.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'late', 'permission') NOT NULL DEFAULT 'present'"
        );

        DB::table('student_attendances')->where('status', 'late')->update(['status' => 'permission']);

        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'permission') NOT NULL DEFAULT 'present'"
        );
    }

    public function down(): void
    {
        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'late', 'permission') NOT NULL DEFAULT 'present'"
        );

        DB::table('student_attendances')->where('status', 'permission')->update(['status' => 'late']);

        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'late') NOT NULL DEFAULT 'present'"
        );
    }
};
