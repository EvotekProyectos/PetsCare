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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->string("anamnesis")->nullable();
            $table->string("exam_details")->nullable();
            $table->string("diagnosis");
            $table->string("observations")->nullable();
            $table->date("day_next_check")->nullable();
            $table->time("time_next_check")->nullable();
            $table->foreignId('reason_next_check_id')->nullable()->references('id')->on('reasons');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
