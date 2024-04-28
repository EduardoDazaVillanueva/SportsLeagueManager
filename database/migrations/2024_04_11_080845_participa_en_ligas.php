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
        Schema::create('participa_en_ligas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('liga_id')->constrained();
            $table->foreignId('jugadores_id')->constrained();
            $table->foreignId('equipo_id')->constrained();
            $table->integer('num_partidos')->unsigned()->default(0);
            $table->integer('num_partidos_ganados')->unsigned()->default(0);
            $table->integer('num_partidos_perdidos')->unsigned()->default(0);
            $table->integer('num_partidos_empatados')->unsigned()->default(0);
            $table->integer('puntos')->unsigned()->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participa_en_ligas');
    }
};
