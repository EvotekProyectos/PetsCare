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
        Schema::create('followup_interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_id')->nullable()->references('id')->on('receptions');
            $table->datetime("date");
            $table->boolean("alterations")->nullable();
            $table->string("which_alterations")->nullable();
            $table->boolean("therapeutic")->nullable();
            $table->string("which_therapeutic")->nullable();
            $table->boolean("vomiting")->nullable();
            $table->string("quantity_vomiting")->nullable();
            $table->boolean("defecation")->nullable();
            $table->string("quantity_defecation")->nullable();
            $table->boolean("urine")->nullable();
            $table->string("quantity_urine")->nullable();
            $table->boolean("feeding")->nullable();
            $table->string("type_feeding")->nullable();
            $table->text("pendings")->nullable();
            $table->boolean("ultrasounds")->nullable();
            $table->string("observations_ultrasounds")->nullable();
            $table->text("observations")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followup_interns');
    }
};
