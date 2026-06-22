<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('i_c_t_invoices', function (Blueprint $table): void {
            $table->string('bakong_txn_ref')->nullable()->after('paid_at');
            $table->string('bakong_hash', 128)->nullable()->after('bakong_txn_ref');
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_invoices', function (Blueprint $table): void {
            $table->dropColumn(['bakong_txn_ref', 'bakong_hash']);
        });
    }
};
