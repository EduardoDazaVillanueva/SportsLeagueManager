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
        Schema::create('patrocinadores_has_ligas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patrocinadores_id')->constrained();
            $table->foreignId('liga_id')->constrained();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrocinadores_has_ligas');
    }
};
