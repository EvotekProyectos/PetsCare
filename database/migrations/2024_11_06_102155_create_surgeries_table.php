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
        Schema::create('surgeries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('surgery_type_id')->nullable()->references('id')->on('product_types');
            $table->dateTime("surgery_date")->nullable();
            $table->longText("surgery_description")->nullable();
            $table->String("preanesthetic")->nullable();
            $table->String("anesthetic")->nullable();
            $table->String("other_medicines")->nullable();
            $table->longText("treatment")->nullable();
            $table->longText("observations")->nullable();
            $table->longText("complications")->nullable();
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
        Schema::dropIfExists('surgeries');
    }
};
