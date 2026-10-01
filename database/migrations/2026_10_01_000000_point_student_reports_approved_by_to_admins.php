<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * student_reports.approved_by was originally constrained against
     * `users`, but the only place that ever writes it --
     * Admin\StudentReportController::approve() -- runs behind the
     * `auth:admin` middleware, which authenticates against the separate
     * `admins` table (config/auth.php: guard 'admin' -> provider 'admins'
     * -> App\Models\Admin). The controller was calling Auth::id() with no
     * guard, which reads the unrelated default `web` guard's session
     * instead -- handing back whatever id happened to be sitting there
     * (even a stale one for a users row that no longer exists), which the
     * FK then rejected with a 1452 violation on every approve click.
     * Fixed alongside this migration: the controller now calls
     * Auth::guard('admin')->id(), so this FK needs to point at the table
     * that id actually comes from.
     */
    public function up(): void
    {
        Schema::table('student_reports', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
        });

        // Self-heal any existing approved_by value that doesn't correspond
        // to a real admin (e.g. one that only ever matched by coincidence
        // against the old `users`-table constraint) -- otherwise it would
        // block adding the new FK below. This only clears who's recorded
        // as having approved a report; it never touches approval_status
        // or the report itself.
        DB::statement('
            UPDATE student_reports
            SET approved_by = NULL
            WHERE approved_by IS NOT NULL
              AND approved_by NOT IN (SELECT id FROM admins)
        ');

        Schema::table('student_reports', function (Blueprint $table) {
            $table->foreign('approved_by')
                ->references('id')->on('admins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_reports', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
        });

        Schema::table('student_reports', function (Blueprint $table) {
            $table->foreign('approved_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }
};
