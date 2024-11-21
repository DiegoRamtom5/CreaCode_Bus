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
        Schema::create('autobus', function (Blueprint $table) {
            $table->id();
            $table->string('numero_autobus');
            $table->string('linea');
            $table->integer('capacidad');
            $table->string('servicios');
            $table->integer('num_incidencia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autobus');
    }
};
