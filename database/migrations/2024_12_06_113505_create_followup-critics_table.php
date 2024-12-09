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
        Schema::create('followups-critics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->string("pet_status")->nullable();
            $table->string("preasure")->nullable();
            $table->string("temperature")->nullable();
            $table->string("glycemia")->nullable();
            $table->boolean("throwup")->nullable();
            $table->string("throwup_detail")->nullable();
            $table->boolean("defecate")->nullable();
            $table->string("defecate_detail")->nullable();
            $table->boolean("orino")->nullable();
            $table->string("orino_detail")->nullable();
            $table->boolean("eat")->nullable();
            $table->string("eat_detail")->nullable();
            $table->boolean("infusions")->nullable();
            $table->string("infusions_detail")->nullable();
            $table->boolean("terapeutic")->nullable();
            $table->string("terapeutic_detail")->nullable();
            $table->boolean("imaging")->nullable();
            $table->string("imaging_detail")->nullable();
            $table->string("pends")->nullable();
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
        //
    }
};
