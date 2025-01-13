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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->date("date");
            $table->string("total")->nullable();
            $table->string("others")->nullable();
            $table->foreignId('vet_id')->nullable()->references('id')->on('users');
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
