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
        Schema::create('ligas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('logo')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->date('fecha_fin_inscripcion');
            $table->string('localidad');
            $table->string('sede');
            $table->integer('dia_jornada')->unsigned();
            $table->integer('pnts_ganar')->unsigned();
            $table->integer('pnts_perder')->unsigned();
            $table->integer('pnts_empate')->unsigned();
            $table->integer('pnts_juego')->unsigned();
            $table->string('txt_responsabilidad');

            $table->foreignId('deporte_id')->constrained();
            $table->foreignId('organizadores_id')->constrained();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligas');
    }
};
