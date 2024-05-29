<?php

use App\Models\Productos;
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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('precio')->nullable();
            $table->string('descripcion');
            $table->foreignId('liga_id')->nullable()->constrained();
            $table->timestamps();
        });


        //Insertar valores
        $productos = [
            ['nombre' => 'Mes organizador', 'precio' => 19.99, 'descripcion' => 'Puedes crear todas las ligas que quieras durante un mes, cuando acabe tu suscripción deberás seguir pagando si deseas que las ligas sigan funcionando', 'liga_id' => null],
            ['nombre' => 'Trimestre organizador', 'precio' => 54.99, 'descripcion' => 'Puedes crear todas las ligas que quieras durante tres meses, cuando acabe tu suscripción deberás seguir pagando si deseas que las ligas sigan funcionando', 'liga_id' => null],
            ['nombre' => 'Año organizador', 'precio' => 199.99, 'descripcion' => 'Puedes crear todas las ligas que quieras durante un año, cuando acabe tu suscripción deberás seguir pagando si deseas que las ligas sigan funcionando', 'liga_id' => null],
            ['nombre' => 'Vitalicia organizador', 'precio' => 499.99, 'descripcion' => 'Puedes crear todas las ligas que quieras de por vida, sin necesidad de pagar suscripciones adicionales', 'liga_id' => null]
        ];

        Productos::insert($productos);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
