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
        Schema::create('cubicles', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("length");
            $table->string("width");
            $table->datetime('start_date')->nullable(); 
            $table->datetime('end_date')->nullable(); 
            $table->foreignId('cubicle_type_id')->nullable()->references('id')->on('cubicle_types');
            $table->boolean('state')->nullable(); 
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cubicles');
    }
};
