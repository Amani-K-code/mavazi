<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
          if (!Schema::hasColumn('inventories', 'is_locked')) {
            $table->boolean('is_locked')->default(false)->after('is_flagged');
          }
        });

        //Update for Sales to have audit columns and expanding status options
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'admin_note')) {
                $table->string('admin_note')->nullable()->after('status');
            }

            if (!Schema::hasColumn('sales', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('admin_note');
            }
        });

        DB::statement("ALTER TABLE sales MODIFY COLUMN status ENUM('PENDING', 'CONFIRMED', 'BOOKED', 'EXPIRED', 'CANCELLED') NOT NULL DEFAULT 'PENDING' ");
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            //
            $table->dropColumn('is_locked');
        });
        Schema::table('sales', function (Blueprint $table) {
            //
            $table->dropColumn(['admin_note', 'resolved_at']);
        });

        DB::statement("ALTER TABLE sales MODIFY COLUMN status ENUM('PENDING', 'CONFIRMED', 'BOOKED', 'EXPIRED') NOT NULL DEFAULT 'PENDING' ");
    }
};
