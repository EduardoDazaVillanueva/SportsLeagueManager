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
                <h1>Los partidos todavia no están creados</h1>
            </div>

            @else
            @foreach ($partidos as $partido)
            <article class="partidos-div_partido">
                <div class="partido_info">
                    <header class="partido_info-header">
                        <p class="partido_fecha">{{$partido->dia}}</p>
                        <p class="partido_hora">{{$partido->hora_inicio}} / {{$partido->hora_final}}</p>
                        <p class="partido_hora">Pista {{$partido->pista}}</p>
                    </header>

                    <div class="partido_resultado">
                        @foreach($jugadores as $jugador)

                        @php
                        $idPartido = $partido->id;
                        @endphp

                        @if ($jugador->partido_id == $idPartido)
                        <h2>{{$jugador->name}}</h2>
                        @endif
                        @endforeach
                    </div>

                    <div class="partido_resultado">
                        <h2>{{$partido->resultado}}</h2>
                    </div>
                </div>
            </article>
            @endforeach
            @endif
        </section>

    </main>
</x-layoutLiga>