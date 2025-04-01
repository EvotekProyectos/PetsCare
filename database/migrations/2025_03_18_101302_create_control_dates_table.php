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
        Schema::create('control_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
   	        $table->foreignId('family_id')->nullable()->references('id')->on('families');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
	        $table->foreignId('date_type_id')->nullable()->references('id')->on('date_types');
   	        $table->foreignId('status_date_id')->nullable()->references('id')->on('status_dates');
            $table->foreignId('schedule_id')->nullable()->references('id')->on('schedules');
            $table->datetime('date');
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_dates');
    }
};
