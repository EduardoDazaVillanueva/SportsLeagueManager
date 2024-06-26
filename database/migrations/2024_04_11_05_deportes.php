<?php

use App\Models\Deportes;
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
        Schema::create('deportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

            $table->timestamps();
        });

        // Insertar valores
        $deportes = [
            ['nombre' => 'Fútbol'],
            ['nombre' => 'Baloncesto'],
            ['nombre' => 'Tenis'],
            ['nombre' => 'Pádel']
        ];

        Deportes::insert($deportes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deportes');
    }
};
