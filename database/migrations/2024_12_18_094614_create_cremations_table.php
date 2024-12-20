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
        Schema::create('cremations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('pet_id')->nullable()->references('id')->on('pets');
            $table->dateTime('date_death')->nullable();
            $table->dateTime('date_finish')->nullable();
            $table->string("servicie")->nullable();
            $table->foreignId("CM_id")->nullable()->references('id')->on('cm_types');
            $table->string("type_urn")->nullable();
            $table->string("urn_model")->nullable();
            $table->text("observations")->nullable();
            $table->foreignId("placa_type_id")->nullable()->references('id')->on('tag_types');
            $table->string('status')->default('En espera de realizar');
            $table->string("price")->nullable();  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cremations');
    }
};
