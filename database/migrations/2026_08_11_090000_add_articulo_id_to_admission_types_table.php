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
        Schema::table('admission_types', function (Blueprint $table) {
            // ARTICULO_ID (Microsip) que se cobra por día de hospitalización bajo
            // este tipo de admisión; mismo patrón que reasons.articulo_id: sin FK
            // real, se resuelve contra Firebird.
            $table->integer('articulo_id')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admission_types', function (Blueprint $table) {
            $table->dropColumn('articulo_id');
        });
    }
};
