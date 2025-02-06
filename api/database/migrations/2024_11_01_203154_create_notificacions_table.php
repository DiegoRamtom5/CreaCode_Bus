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
        Schema::create('notificacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_boleto');
            $table->unsignedBigInteger('id_incidente')->nullable();
            $table->integer('tipo');
            $table->text('mensaje');
            $table->dateTime('fecha_envio');
            $table->timestamps();

            $table->foreign('id_boleto')->references('id')->on('boleto')->onDelete('cascade');
            $table->foreign('id_incidente')->references('id')->on('incidente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacion');
    }
};
