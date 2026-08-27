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
        Schema::table('voucher_products', function (Blueprint $table) {
            $table->dropForeign(['red_sheet_id']);
            $table->dropColumn('red_sheet_id');
            $table->unsignedBigInteger('sourceable_id');
            $table->string('sourceable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_products', function (Blueprint $table) {
            $table->dropColumn(['sourceable_id', 'sourceable_type']);
            $table->foreignId('red_sheet_id')->references('id')->on('red_sheets');
        });
    }
};
