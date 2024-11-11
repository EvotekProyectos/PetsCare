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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string("number_chip")->nullable();
            $table->foreignId('family_id')->nullable()->references('id')->on('families');
            $table->string("name");
            $table->foreignId('picture_id')->nullable()->references('id')->on('files');
            $table->string("specie");
            $table->string("raza")->nullable();
            $table->foreignId('gender_id')->nullable()->references('id')->on('genres');
            $table->date('birthday')->nullable();
            $table->foreignId('reproductive_status_id')->nullable()->references('id')->on('reproductive_statuses');
            $table->string("weight")->nullable();
            $table->string("physic_descrip")->nullable();
            $table->string("notes")->nullable();
            $table->foreignId('pet_classification_id')->nullable()->references('id')->on('pet_classifications');
            $table->boolean('deceased')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
