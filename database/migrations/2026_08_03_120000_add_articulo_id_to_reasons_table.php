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
        Schema::table('reasons', function (Blueprint $table) {
            // ARTICULO_ID (Microsip) del concepto que se cobra por defecto en
            // una recepción de Consulta con este motivo; mismo patrón que
            // Appointment.service_id / Charge.product_id: sin FK real, se
            // resuelve contra Firebird.
            $table->integer('articulo_id')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reasons', function (Blueprint $table) {
            $table->dropColumn('articulo_id');
        });
    }
};
