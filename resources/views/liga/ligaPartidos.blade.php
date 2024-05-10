<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <h1 class="jugadores_titulo">Partidos</h1>

        <header class="header_partido">
            <form action="{{ route('liga.partidos', ['liga' => $liga->id]) }}" method="GET">
                <select name="num_jornada" id="jornada_select" onchange="this.form.submit()" class="partido_select">
                    @foreach ($jornadas as $jornada)
                    <option value="{{ $jornada->num_jornada }}" {{ $numJornada == $jornada->num_jornada ? 'selected' : '' }}>
                        Jornada {{ $jornada->num_jornada }}
                    </option>
                    @endforeach
                </select>
            </form>
            <p class="header_partido-fecha">{{$fechaInicio}} / {{$fechaFinal}}</p>
        </header>

        <section class="container-partidos">
            @if (count($partidos) == 0)
            <div class="sin-partidos">
                <h1>Los partidos todavía no están creados</h1>
            </div>
            @else
            @foreach ($partidos as $partido)
            <article class="partidos-div_partido">
                <div class="partido_info">
                    <header class="partido_info-header">
                        <p class="partido_fecha">{{ $partido->dia }}</p>
                        <p class="partido_hora">{{ $partido->hora_inicio }} / {{ $partido->hora_final }}</p>
                        <p class="partido_pista">Pista {{ $partido->pista }}</p>
                    </header>

                    <div class="partido_informacion">
                        @php
                        $idPartido = $partido->id;
                        $participaUsuario = false;
                        $contador = 0; // Iniciamos el contador
                        @endphp

                        <div class="lineaVertical"></div>

                        <!-- Empezamos el primer bloque para la primera pareja -->
                        @foreach($jugadores as $jugador)
                        @if ($jugador->partido_id == $idPartido)

                        @if ($contador == 0)
                        <!-- Abrimos el div para la primera pareja -->
                        <div class="pareja_nombre">
                            @endif

                            <h2 class="{{ $jugador->id == $user->id ? 'resalto' : '' }}">{{ $jugador->name }}</h2>

                            @php
                            if ($jugador->id == $user->id) {
                            $participaUsuario = true;
                            }
                            $contador++;
                            @endphp

                            @if ($contador == ($jugadoresPorPartido / 2))
                        </div> <!-- Cerramos el primer bloque -->
                        <div class="lineaVertical"></div>
                        <!-- Abrimos el div para la segunda pareja -->
                        <div class="pareja_nombre">
                            @endif

                            @if ($contador == $jugadoresPorPartido)
                        </div> <!-- Cerramos el div de la segunda pareja -->
                        @endif

                        @endif
                        @endforeach
                    </div>

                    @if ($participaUsuario)
                    <div class="partido_resultado">
                        <form action="/liga/{{$liga->id}}/resultado/{{$idPartido}}">
                            <button type="submit" class="btnResultado">Añadir resultado</button>
                        </form>
                    </div>
                    @else
                    <div class="partido_resultado">
                    </div>
                    @endif

                </div>
            </article>
            @endforeach
            @endif

        </section>
    </main>
</x-layoutLiga>