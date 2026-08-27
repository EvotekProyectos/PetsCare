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
        Schema::table('red_sheets', function (Blueprint $table) {
            $table->dropColumn('add_voucher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('red_sheets', function (Blueprint $table) {
            $table->boolean('add_voucher')->default(false);
        });
    }
};
