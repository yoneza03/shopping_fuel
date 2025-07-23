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
        Schema::table('fuel_records', function (Blueprint $table) {
            $table->decimal('fuel_price', 6, 2)->nullable();      // 単価 (円/L)
            $table->decimal('total_cost', 8, 2)->nullable();       // 支払い金額
            $table->string('note', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_records', function (Blueprint $table) {
            //
        });
    }
};
