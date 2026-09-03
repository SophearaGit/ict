<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds an explicit 'unmarked' status to student_attendances.status.
 *
 * Previously "unmarked" was only ever implicit — a student with no row
 * for that course+date. That meant a save where nothing was actually
 * clicked (every student left on the default state) silently wrote
 * ZERO rows, which made that day's session invisible to the Session Log
 * query (it only lists dates that have at least one row). Saving now
 * always writes one row per enrolled student, using 'unmarked' for
 * anyone left unclicked, so a session that was genuinely visited/saved
 * always shows up — instead of only showing up if at least one student
 * happened to get a real status.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'permission', 'unmarked') NOT NULL DEFAULT 'unmarked'"
        );
    }

    public function down(): void
    {
        // 'unmarked' rows have no equivalent in the narrower enum — they
        // represented "no real attendance data" anyway (the same as no
        // row at all, which is how the app behaved before this migration),
        // so they're removed rather than remapped to a real status.
        DB::table('student_attendances')->where('status', 'unmarked')->delete();

        DB::statement(
            "ALTER TABLE student_attendances MODIFY status ENUM('present', 'absent', 'permission') NOT NULL DEFAULT 'present'"
        );
    }
};
