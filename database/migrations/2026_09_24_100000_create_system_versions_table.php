<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Contador de versión por clave, usado para reemplazar los 5 MAX(updated_at)
 * de ReceptionController::lastUpdateGlobal() por una sola lectura (ver
 * ReceptionVersionService). 'key' es UNIQUE porque en el futuro este mismo
 * mecanismo podría usarse para otros módulos con polling (Hospital,
 * Consultas, Almacén, ver los otros usos de pollForChanges en global.js) sin
 * crear una tabla nueva por cada uno -aunque esta migración solo siembra la
 * fila 'receptions' que se usa hoy-.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_versions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedBigInteger('version')->default(0);
            $table->timestamps();
        });

        // Se siembra aquí (no en un seeder aparte) para que la fila exista
        // apenas se corre la migración, sin depender de que alguien visite
        // Recepciones primero -lastUpdateGlobal() es un endpoint de solo
        // lectura y no debe crear la fila en cada polling (ver
        // ReceptionController::lastUpdateGlobal()).
        DB::table('system_versions')->insert([
            'key' => 'receptions',
            'version' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_versions');
    }
};
