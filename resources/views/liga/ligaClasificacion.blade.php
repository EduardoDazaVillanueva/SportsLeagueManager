<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <h1 class="jugadores_titulo">Clasificación</h1>

        <section class="jugadores">
            <table class="jugadores_tabla">
                <thead>
                    <tr class="tabla_cabecera">
                        <th class="tabla_titulo">Posición</th>
                        <th class="tabla_titulo">Nombre</th>
                        <th class="tabla_titulo">Partidos jugados</th>
                        <th class="tabla_titulo">Partidos ganados</th>
                        <th class="tabla_titulo">Partidos empatados</th>
                        <th class="tabla_titulo">Partidos perdidos</th>
                        <th class="tabla_titulo">Puntos</th>
                    </tr>
                </thead>


                <tbody>
                    @php
                    $posicion = 1;
                    @endphp

                    @foreach ($jugadores as $jugador)

                    <tr class="tabla_datos">
                        <td class="tabla_dato">{{$posicion}}</td>
                        <td class="tabla_dato">{{$jugador["user_name"]}}</td>
                        <td class="tabla_dato">{{$jugador["num_partidos"]}}</td>
                        <td class="tabla_dato">{{$jugador["num_partidos_ganados"]}}</td>
                        <td class="tabla_dato">{{$jugador["num_partidos_empatados"]}}</td>
                        <td class="tabla_dato">{{$jugador["num_partidos_perdidos"]}}</td>
                        <td class="tabla_dato">{{$jugador["puntos"]}}</td>
                    </tr>

                    @php
                    $posicion++;
                    @endphp

                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
</x-layoutLiga>