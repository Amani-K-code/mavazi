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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('category', 100);
            $table->string('size_label', 50);
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(40); //Initial test stock quantity
            $table->integer('reserved_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->boolean('is_flagged')->default(false);
            $table->timestamps();
            $table->index(['item_name', 'size_label']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
