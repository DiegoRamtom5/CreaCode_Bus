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
        Schema::create('corrida', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_autobus');
            $table->string('origen');
            $table->string('destino');
            $table->date('fecha');
            $table->time('hora_salida');
            $table->time('hora_estima_llegada');
            $table->integer('tipo_corrida');
            $table->json('asientos');
            $table->decimal('precio');
            $table->timestamps();

            $table->foreign('id_autobus')->references('id')->on('autobus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrida');
    }
};
