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
            $table->foreignId('surgery_pack_id')->nullable()->references('id')->on('surgery_packs');
            $table->string("procedure");
            $table->double("procedure_price");
            $table->boolean("biometric")->nullable();
            $table->string("biometric_price")->nullable();
            $table->integer("chemistry")->nullable();
            $table->string("chemistry_price")->nullable();
            $table->boolean("nodulectomy")->nullable();
            $table->string("nodulectomy_price")->nullable();
            $table->boolean("histopathology")->nullable();
            $table->string("histopathology_price")->nullable();
            $table->boolean("xrays")->nullable();
            $table->string("xrays_price")->nullable();
            $table->boolean("collar")->nullable();
            $table->string("collar_price")->nullable();
            $table->boolean("body")->nullable();
            $table->string("body_price")->nullable();
            $table->string("others")->nullable();
            $table->string("total")->nullable();
            $table->foreignId('vet_id')->nullable()->references('id')->on('users');
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
