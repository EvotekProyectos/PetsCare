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
        Schema::create('surgery_packs', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("total");
            $table->string("catheterization_price");
            $table->string("preanesthetic_price");
            $table->string("monitoring_price");
            $table->string("surgical_clothing_price");
            $table->string("preparations_price");
            $table->string("observation_price");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surgery_packs');
    }
};
