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
            <p class="header_partido-fecha">{{ $fechaInicio }} / {{ $fechaFinal }}</p>
        </header>

        <section class="container-partidos">
            @if (count($partidos) == 0)
            <div class="sin-partidos">
                <h1>Los partidos todavía no están creados</h1>
            </div>
            @else
            @foreach ($partidos as $partido)
            @php
            $resultado = $partido->resultado; // Suponiendo que $partido->resultado es una cadena de texto

            // Convertir la cadena en un array
            $datos = array_map(function ($dato) {
            $dato = trim($dato, '[]"');
            // Verificar si el número tiene más de un dígito y el primer dígito es "0"
            if (strlen($dato) > 1 && $dato[0] === '0') {
            // Eliminar el "0" del principio
            return substr($dato, 1);
            }
            return $dato;
            }, explode(',', $resultado));

            $pareja1 = [];
            $pareja2 = [];

            foreach ($datos as $indice => $dato) {
            if ($indice % 2 == 0) {
            $pareja1[] = $dato;
            } else {
            $pareja2[] = $dato;
            }
            }
            @endphp

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

                        <!-- Empezamos el primer bloque para la primera pareja -->
                        @foreach ($jugadores as $jugador)
                        @if ($jugador->partido_id == $idPartido)
                        @if ($contador == 0)
                        <!-- Abrimos el div para la primera pareja -->
                        <div class="pareja_nombre">
                            @endif

                            <h2 class="{{ $jugador->id == $user->id ? 'resalto' : '' }}">
                                {{ $jugador->name }}
                            </h2>

                            @php
                            if ($jugador->id == $user->id) {
                            $participaUsuario = true;
                            }
                            $contador++;
                            @endphp

                            @if ($contador == $jugadoresPorPartido / 2)
                        </div> <!-- Cerramos el primer bloque -->

                        <div class="partido-centro">
                            <div class="puntos_p1">
                                @foreach ($pareja1 as $p1)
                                <p>{{ $p1 }}</p>
                                @endforeach
                            </div>

                            <div class="lineaVertical"></div>

                            <div class="puntos_p2">
                                @foreach ($pareja2 as $p2)
                                <p>{{ $p2 }}</p>
                                @endforeach
                            </div>
                        </div>

                        <!-- Abrimos el div para la segunda pareja -->
                        <div class="pareja_nombre">
                            @endif

                            @if ($contador == $jugadoresPorPartido)
                        </div> <!-- Cerramos el div de la segunda pareja -->
                        @endif
                        @endif
                        @endforeach
                    </div>

                    @if ($participaUsuario && $partido->resultado == '')
                    <div class="partido_resultado">
                        <form action="/liga/{{ $liga->id }}/resultado/{{ $idPartido }}">
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

        @if (session('success'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('success')}}</h2>
            </div>
        </div>
        @endif
    </main>
</x-layoutLiga>