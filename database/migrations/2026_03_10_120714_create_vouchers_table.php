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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->enum('status', ['Pendiente', 'Surtido', 'Rechazado', 'Cancelado'])->default('Pendiente');
            $table->foreignId('reception_id')->references('id')->on('receptions');
            $table->foreignId('vet_id')->references('id')->on('users');
            $table->foreignId('issuer_id')->nullable()->references('id')->on('users');
            $table->datetime('issued_at')->nullable();
            $table->string('generated_document')->nullable();
            $table->string('vet_signature')->nullable();
            $table->string('cancellation_signature')->nullable();
            $table->foreignId('cancelled_by')->nullable()->references('id')->on('users');
            $table->string('warehouse_signature')->nullable();
            $table->text('warehouse_observations')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
