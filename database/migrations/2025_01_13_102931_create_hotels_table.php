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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('vaccine_certificate_id')->nullable()->references('id')->on('vaccine_certificates');
            $table->text("food");
            $table->text("objects");
            $table->text("observations");
            $table->integer("number_days");
            $table->integer('service_type_id')->nullable();
            $table->foreignId('cubicle_id')->nullable()->references('id')->on('cubicles');
            $table->dateTime('finish_date')->nullable();
            $table->boolean('video')->default(0);
            $table->boolean('state')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
