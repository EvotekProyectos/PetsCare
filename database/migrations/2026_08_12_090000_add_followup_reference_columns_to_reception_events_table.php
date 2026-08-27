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
            // Solo se llenan cuando event_type = 'followup_added': identifican
            // qué registro específico de seguimiento originó el evento, para
            // poder pintar su detalle en modo solo lectura desde la bitácora
            // (ver RedSheetController::events() / GET follow-ups/detail).
            // Sin FK: reception_events no es polimórfico y followup_id apunta
            // a una de 4 tablas distintas según followup_type.
            $table->string('followup_type')->nullable()->after('description');
            $table->unsignedBigInteger('followup_id')->nullable()->after('followup_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reception_events', function (Blueprint $table) {
            $table->dropColumn(['followup_type', 'followup_id']);
        });
    }
};
