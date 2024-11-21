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
        Schema::create('boleto', function (Blueprint $table) {
            $table->id();
            $table->string('num_boleto');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_corrida')->nullable();
            $table->integer('num_asientos');
            $table->date('fecha_compra');
            $table->decimal('monto', 8, 2);
            $table->decimal('descuento', 8, 2);
            $table->unsignedInteger('id_pago');
            $table->integer('estado');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_corrida')->references('id')->on('corrida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boleto');
    }
};
