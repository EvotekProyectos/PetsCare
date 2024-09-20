<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_type_id')->nullable()->references('id')->on('reception_types');
            $table->foreignId('admission_type_id')->nullable()->references('id')->on('admission_types');
            $table->foreignId('area_id')->nullable()->references('id')->on('areas');
            $table->foreignId('family_id')->nullable()->references('id')->on('families');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->foreignId('reason_id')->nullable()->references('id')->on('reasons');
            $table->foreignId('veterinarian_id')->nullable()->references('id')->on('users');
            $table->foreignId('recepcionist_id')->nullable()->references('id')->on('users');
            $table->foreignId('room_id')->nullable()->references('id')->on('rooms');
            $table->date('entry_date');
            $table->date('exit_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receptions');
    }
};
