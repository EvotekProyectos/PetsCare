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
            $table->timestamp('removed_at')->nullable()->after('vet_id');
            $table->foreignId('removed_by')->nullable()->after('removed_at')->references('id')->on('users');
            $table->text('removal_reason')->nullable()->after('removed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('red_sheets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('removed_by');
            $table->dropColumn(['removed_at', 'removal_reason']);
        });
    }
};
