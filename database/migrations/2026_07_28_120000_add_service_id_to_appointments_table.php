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
        Schema::table('appointments', function (Blueprint $table) {
            // ARTICULO_ID (Microsip) del servicio que el médico registra al
            // finalizar la consulta; mismo patrón que Grooming.service_id /
            // Hotel.service_type_id / Cremation.servicie: AccountStatementService
            // lo lee al cerrar cuenta. Nullable porque las consultas ya
            // existentes no lo tienen capturado.
            $table->integer('service_id')->nullable()->after('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });
    }
};
