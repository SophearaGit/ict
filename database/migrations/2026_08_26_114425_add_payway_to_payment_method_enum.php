<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * MySQL enum columns can't be widened through Schema::table()/change()
 * without doctrine/dbal, so this uses a raw MODIFY COLUMN statement.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE i_c_t_payments MODIFY payment_method ENUM('cash', 'bank_transfer', 'card', 'online', 'payway') NOT NULL"
        );
    }

    public function down(): void
    {
        // Reverting will fail if any row already has payment_method = 'payway'.
        // Reassign those rows (e.g. to 'online') before rolling back if needed.
        DB::statement(
            "ALTER TABLE i_c_t_payments MODIFY payment_method ENUM('cash', 'bank_transfer', 'card', 'online') NOT NULL"
        );
    }
};
