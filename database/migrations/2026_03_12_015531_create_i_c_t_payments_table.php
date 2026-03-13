<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('i_c_t_payments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('i_c_t_invoices')
                ->cascadeOnDelete();

            $table->double('amount');

            $table->enum('payment_method', [
                'cash',
                'bank_transfer',
                'card',
                'online'
            ]);
            $table->text('note')->nullable();

            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_c_t_payments');
    }
};
