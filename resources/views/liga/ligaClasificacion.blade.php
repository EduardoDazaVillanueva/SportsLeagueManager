<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <h1 class="jugadores_titulo">Clasificación</h1>

        <section class="jugadores">
            <table class="jugadores_tabla">
                <tr class="tabla_cabecera">
                    <th class="tabla_titulo">Posición</th>
                    <th class="tabla_titulo">Nombre</th>
                    <th class="tabla_titulo">Partidos jugados</th>
                    <th class="tabla_titulo">Partidos ganados</th>
                    <th class="tabla_titulo">Partidos empatados</th>
                    <th class="tabla_titulo">Partidos perdidos</th>
                    <th class="tabla_titulo">Puntos</th>
                </tr>


                @php
                $posicion = 1;
                @endphp

                @foreach ($jugadores as $index => $jugador)
                <tr class="tabla_datos {{ $index % 2 ? 'gris' : '' }}">
                    <td class="tabla_dato">{{ $posicion }}</td>
                    <td class="tabla_dato">
                        @if ($liga->deporte_id == 3 || $liga->deporte_id == 4)
                        <a class=" {{ $jugador->user_id == $user->id ? 'resalto' : '' }}" href="/perfil/{{ $jugador->user_id }}">{{ $jugador["user_name"] }}</a>
                        @else
                        <span class="{{ $perteneceEquipo != null ? 'resalto' : '' }}">{{ $jugador["user_name"] }}</span>
                        @endif
                    </td>
                    <td class="tabla_dato">{{ $jugador["num_partidos"] }}</td>
                    <td class="tabla_dato">{{ $jugador["num_partidos_ganados"] }}</td>
                    <td class="tabla_dato">{{ $jugador["num_partidos_empatados"] }}</td>
                    <td class="tabla_dato">{{ $jugador["num_partidos_perdidos"] }}</td>
                    <td class="tabla_dato">{{ $jugador["puntos"] }}</td>
                </tr>

                @php
                $posicion++;
                @endphp
                @endforeach

            </table>
        </section>
    </main>
</x-layoutLiga>