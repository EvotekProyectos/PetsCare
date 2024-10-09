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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('veterinarian_id')->nullable()->references('id')->on('users');
            $table->foreignId('recepcionist_id')->nullable()->references('id')->on('users');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->dateTime('date');
            $table->String('medicine'); 
            $table->text('diagnosis');
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
