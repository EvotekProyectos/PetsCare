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
        Schema::create('general_groomings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->longText('instructions')->nullable();
            $table->dateTime('next_service');
            $table->boolean('critic_status');
            $table->boolean('delivery_service');
            $table->longText('delivery_references')->nullable();
            $table->integer('folio');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_groomings');
    }
};
