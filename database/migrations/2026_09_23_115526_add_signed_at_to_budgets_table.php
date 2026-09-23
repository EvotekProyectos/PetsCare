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
        Schema::table('budgets', function (Blueprint $table) {
            // Momento en que ESTE Budget específico quedó firmado (ver
            // BudgetController::budgetpdf()). Antes "firmado" solo se
            // inferí­a creando un Format ligado a reception_id, pero Format
            // no tiene budget_id — con varios Budget independientes por
            // consulta (ver BudgetDetailController::storeBatch()) eso ya no
            // alcanza para saber CUÁL de ellos fue el firmado. Es la fuente
            // de verdad que usa BudgetConversionService para decidir qué
            // servicios son convertibles en Red Sheet.
            $table->timestamp('signed_at')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('signed_at');
        });
    }
};
