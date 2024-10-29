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
        Schema::create('vaccine_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->foreignId('service_id')->nullable()->references('id')->on('services');
            $table->string("product")->nullable();
            $table->string("lab")->nullable();
            $table->string("lote")->nullable();
            $table->string("dose")->nullable();
            $table->date("application_date")->nullable();
            $table->date("last_deworming_date")->nullable();
            $table->date("next_application_date")->nullable();
            $table->text("observations")->nullable();
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
        Schema::dropIfExists('vaccine_certificates');
    }
};
