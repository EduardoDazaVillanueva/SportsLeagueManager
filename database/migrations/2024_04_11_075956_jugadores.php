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
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id();
            $table->integer('num_partidos')->unsigned();
            $table->integer('num_partidos_ganados')->unsigned();
            $table->integer('num_partidos_perdidos')->unsigned();
            $table->integer('num_partidos_empatados')->unsigned();
            $table->integer('puntos')->unsigned();
            $table->foreignId('user_id')->constrained();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
