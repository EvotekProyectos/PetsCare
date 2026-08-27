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
        Schema::create('reception_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_reception_id')->nullable()->references('id')->on('receptions');
            $table->foreignId('to_reception_id')->nullable()->references('id')->on('receptions');
            $table->string('reason')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_transfers');
    }
};
