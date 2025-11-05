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
        Schema::table('transactions', function (Blueprint $table) {
            // если знаем имя
            $table->dropForeign(['user_id']);
        });
        Schema::table('user_balances', function (Blueprint $table) {
            // если знаем имя
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
