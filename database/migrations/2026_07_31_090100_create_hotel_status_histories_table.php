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
        Schema::create('hotel_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->references('id')->on('receptions');
            $table->foreignId('hotel_status_id');
            $table->foreign('hotel_status_id', 'fk_htsh_status')->references('id')->on('hotel_statuses');
            $table->foreignId('changed_by')->references('id')->on('users');
            $table->timestamp('changed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_status_histories');
    }
};
