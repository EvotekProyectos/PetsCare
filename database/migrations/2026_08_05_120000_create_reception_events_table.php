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
        Schema::create('reception_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->references('id')->on('receptions');
            $table->string('event_type'); // 'admission_change' | 'followup_added' | 'transfer'
            $table->text('description');
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reception_events');
    }
};
