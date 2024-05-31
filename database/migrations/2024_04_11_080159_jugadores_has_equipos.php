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
        Schema::create('jugadores_has_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained();

            $table->unsignedBigInteger('jugador_id');
            
            $table->foreign('jugador_id')->references('id')->on('jugadores');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores_has_equipos');
    }
};
