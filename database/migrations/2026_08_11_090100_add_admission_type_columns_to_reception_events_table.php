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
        Schema::table('reception_events', function (Blueprint $table) {
            // Solo se llenan cuando event_type = 'admission_change' (ver
            // ReceptionController::transfer()): permiten reconstruir la línea de
            // tiempo de admisiones de una hospitalización sin parsear description,
            // para el cobro por tramos (AccountStatementService::hospitalizacionArticulos()).
            $table->foreignId('from_admission_type_id')->nullable()->after('description')
                ->references('id')->on('admission_types');
            $table->foreignId('to_admission_type_id')->nullable()->after('from_admission_type_id')
                ->references('id')->on('admission_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reception_events', function (Blueprint $table) {
            $table->dropColumn(['from_admission_type_id', 'to_admission_type_id']);
        });
    }
};
