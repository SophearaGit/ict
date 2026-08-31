<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('i_c_t_payments', function (Blueprint $table): void {
            // PayWay's bank reference (e.g. "100FT30148462274") and approval
            // code (apv) — needed to print a real receipt, not just a note.
            $table->string('gateway_reference')->nullable()->after('payment_method');
            $table->string('gateway_approval_code')->nullable()->after('gateway_reference');
            // Full transaction-detail response from PayWay, kept for audit /
            // dispute resolution and so a receipt can be regenerated later
            // without re-calling PayWay.
            $table->json('gateway_response')->nullable()->after('gateway_approval_code');
        });
    }

    public function down(): void
    {
        Schema::table('i_c_t_payments', function (Blueprint $table): void {
            $table->dropColumn(['gateway_reference', 'gateway_approval_code', 'gateway_response']);
        });
    }
};
