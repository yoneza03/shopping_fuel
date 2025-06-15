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
        Schema::create('family_sharing', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id');
        $table->unsignedBigInteger('shared_user_id');
        $table->string('role');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_sharing');
    }
};
