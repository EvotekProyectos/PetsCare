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
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('format_type_id')->nullable()->references('id')->on('format_types');
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->text('format_pdf');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
};
