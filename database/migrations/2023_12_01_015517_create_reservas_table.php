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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->integer("numero_personas");
            $table->date("fecha_llegada");
            $table->integer("cantidad_noche");
            $table->integer("valor_reserva");
            $table->string("estado_reserva", 45)->default('provisional');
            $table->unsignedBigInteger("clientes_id");
            $table->foreign('clientes_id')->references('id')->on("clientes");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
