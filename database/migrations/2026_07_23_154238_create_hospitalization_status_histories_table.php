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
        Schema::create('hospitalization_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->references('id')->on('receptions');
            $table->foreignId('hospitalization_status_id');
            $table->foreign('hospitalization_status_id', 'fk_hsh_status')->references('id')->on('hospitalization_statuses');
            $table->foreignId('changed_by')->references('id')->on('users');
            $table->timestamp('changed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalization_status_histories');
    }
};
