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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['LOW_STOCK', 'EXPIRY_WARNING', 'SYSTEM_NOTE', 'DELIVERY']);
            $table->foreignId('user_id')->nullable()->constrained(); // Sender
            $table->enum('receiver_role', ['Admin', 'Storekeeper', 'Cashier', 'All']);
            $table->text('message');
            $table->integer('related_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
