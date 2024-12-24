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
        Schema::create('grooming_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('grooming_status_id')->nullable()->references('id')->on('grooming_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grooming_status_histories');
    }
};
