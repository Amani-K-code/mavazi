<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 50)->unique();
            $table->string('customer_name');
            $table->string('child_name');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['M-PESA', 'PDQ', 'PRE-PAID']);
            $table->string('reference_id', 100)->unique();
            $table->enum('status', ['PENDING', 'CONFIRMED', 'BOOKED', 'EXPIRED'])->default('PENDING');
            $table->foreignId('user_id')->constrained(); // Links to cashier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
