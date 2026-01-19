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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id_alias', 10)->unique()->after('id');
            $table->enum('role', ['Admin', 'Storekeeper', 'Cashier'])->default('Cashier')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn(['user_id_alias', 'role']);
        });
    }
};
