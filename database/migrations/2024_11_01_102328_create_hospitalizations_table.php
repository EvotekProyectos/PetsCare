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
        Schema::create('hospitalizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('hospital_discharges_id')->nullable()->references('id')->on('hospital_discharges');
            $table->string("reason")->nullable();
            $table->integer("total_days")->nullable();
            $table->decimal("total_payment",8,2)->nullable();
            $table->decimal("already_paid",8,2)->nullable();
            $table->dateTime('exit_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalizations');
    }
};
