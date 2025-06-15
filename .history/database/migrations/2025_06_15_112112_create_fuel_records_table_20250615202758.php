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
        Schema::create('fuel_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('vehicle_id');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->float('fuel_amount');
        $table->float('distance');
        $table->float('fuel_efficiency')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_records');
    }
};
