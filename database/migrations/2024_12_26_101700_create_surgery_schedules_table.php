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
        Schema::create('surgery_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('family_id')->nullable()->references('id')->on('families');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->string('surgical_procedures_type_id')->nullable();
            $table->date("day");
            $table->time("hour");
            $table->string("number_ticket")->nullable();
            $table->foreignId('veterinarian_id')->nullable()->references('id')->on('users');
            $table->foreignId('status_surgery_id')->nullable()->references('id')->on('status_surgeries');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surgery_schedules');
    }
};
