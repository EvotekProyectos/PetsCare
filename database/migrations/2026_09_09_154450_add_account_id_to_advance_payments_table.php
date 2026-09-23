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
        Schema::table('advance_payments', function (Blueprint $table) {
            // Nullable: los anticipos históricos no tienen cuenta todavía
            // (se resuelven en un backfill aparte, ver comando
            // advance-payments:backfill-accounts). Sin onDelete explícito,
            // igual que charges.account_id/payments.account_id/
            // sales_orders.account_id, que ya siguen este mismo patrón.
            $table->foreignId('account_id')->nullable()->after('reception_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('account_id');
        });
    }
};
