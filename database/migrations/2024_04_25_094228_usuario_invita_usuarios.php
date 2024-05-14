<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuario_invita_usuarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_invita');  // Usuario que invita
            $table->string('user_invitado'); // Usuario invitado
        
            // Definición de claves foráneas
            $table->foreign('user_invita')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
