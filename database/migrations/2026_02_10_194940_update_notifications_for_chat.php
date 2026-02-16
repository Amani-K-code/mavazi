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
        Schema::table('notifications', function(Blueprint $table) {
        // Only add if they don't exist
        if (!Schema::hasColumn('notifications', 'sender_id')) {
            $table->foreignId('sender_id')->nullable()->constrained('users');
        }
        if (!Schema::hasColumn('notifications', 'receiver_id')) {
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
        }
        
        // If 'type' already exists it is not added again
        if (!Schema::hasColumn('notifications', 'type')) {
            $table->string('type')->default('CHAT');
        }

        if (!Schema::hasColumn('notifications', 'is_read')) {
            $table->boolean('is_read')->default(false);
        }

        if (!Schema::hasColumn('notifications', 'receiver_role')) {
            $table->string('receiver_role')->nullable();
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
