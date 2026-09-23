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
        Schema::table('budget_details', function (Blueprint $table) {
            // Trazabilidad de conversión de una línea de presupuesto en un
            // servicio real (RedSheet), ver BudgetConversionService::convert().
            // Polimórfico a mano (en vez de morphs()) porque las columnas deben
            // quedar nullable: la mayoría de las líneas nunca se convierten.
            $table->timestamp('converted_at')->nullable()->after('notes');
            $table->string('converted_to_type')->nullable()->after('converted_at');
            $table->unsignedBigInteger('converted_to_id')->nullable()->after('converted_to_type');
            $table->index(['converted_to_type', 'converted_to_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_details', function (Blueprint $table) {
            $table->dropIndex(['converted_to_type', 'converted_to_id']);
            $table->dropColumn(['converted_at', 'converted_to_type', 'converted_to_id']);
        });
    }
};
