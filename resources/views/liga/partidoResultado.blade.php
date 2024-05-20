<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <h1 class="jugadores_titulo">Introduce el resultado del partido</h1>

        <form class="form_resultado"
            action="{{ route('liga.addResultado', ['liga' => $liga->id, 'partido' => $partidos->id]) }}" method="post">
            @csrf

            @php
                $numJugadores = count($jugadores);
                $jugadoresPorPareja = ceil($numJugadores / 2);
            @endphp

            <div class="resultado_parejas">
                <div class="parejas_container">
                    <div class="pareja_nombre-resultado">
                        @foreach ($jugadores as $index => $jugador)
                            @if ($index < $jugadoresPorPareja)
                                <p>{{ $jugador->name }}</p>
                            @endif
                            <input type="hidden" name="jugadores[]" value="{{$jugador->id}}">
                        @endforeach
                    </div>

                    <div class="pareja_div-inputs">
                        @for ($i = 1; $i <= $sets; $i++)
                        <div class="div-input">
                            <p>Set {{$i}}</p>
                            <input type="number" name="pareja1[]" class="partido_input" oninput="checkInput(this)" value="0">
                        </div>
                        @endfor
                    </div>
                </div>
                <div class="lineaVertical-resultado"></div>
                <div class="parejas_container pareja2">
                    <div class="pareja_nombre-resultado">
                        @foreach ($jugadores as $index => $jugador)
                            @if ($index >= $jugadoresPorPareja)
                                <p>{{ $jugador->name }}</p>
                            @endif
                        @endforeach
                    </div>


                    <div class="pareja_div-inputs">
                        @for ($i = 1; $i <= $sets; $i++)
                        <div class="div-input">
                            <p>Set {{$i}}</p>
                            <input type="number" name="pareja2[]" class="partido_input" oninput="checkInput(this)" value="0">
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
            <div class="btn_resultado">
                <button type="submit">Enviar resultado</button>
            </div>
        </form>

        @if (session('error'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('error')}}</h2>
            </div>
        </div>
        @endif
    </main>
</x-layoutLiga>
