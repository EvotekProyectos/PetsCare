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
        Schema::create('pet_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->references('id')->on('pets');
            // Nullable a propósito: la mayoría de los registros vienen de una
            // consulta, pero no todos (ver flujos futuros de captura fuera de
            // consulta) — nunca se debe inventar una recepción.
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->decimal('weight', 8, 2);
            // Momento real de la medición, siempre asignado por el backend
            // (now()) — nunca capturado por el usuario.
            $table->timestamp('measured_at')->useCurrent();
            // Nullable: igual que reception_id, para no bloquear futuros
            // flujos no interactivos. Quién registró el peso, no un
            // duplicado del nombre del médico (eso ya se resuelve vía
            // reception->vet).
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_weights');
    }
};
