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
        Schema::create('i_c_t_invoices', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('course_id')->constrained('i_c_t_courses');
            $table->double('price');
            $table->double('discount')->default(0)->nullable();
            $table->double('total_amount');
            $table->double('paid_amount')->default(0)->nullable();
            $table->double('remaining_amount')->default(0);
            $table->string('invoice_code')->unique();
            $table->enum('payment_status', ['paid', 'half_paid', 'unpaid'])->default('unpaid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_c_t_invoices');
    }
};
