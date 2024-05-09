<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <h1 class="jugadores_titulo">Introduce el resultado del partido</h1>

        <div class="divResultado">
            <form action="">

                @php
                $numJugadores = count($jugadores);
                $jugadoresPorPareja = ceil($numJugadores / 2);
                @endphp
                <div class="divResultado_parejas">
                    <div class="parejas_pareja">
                        <div class="pareja_nombre-resultado">
                            @foreach($jugadores as $index => $jugador)
                            @if ($index < $jugadoresPorPareja) <p>{{ $jugador->name }}</p>
                                @endif
                                @endforeach
                        </div>

                        <div class="pareja_div-inputs">
                            <div class="div-input">
                                <p>Set 1</p>
                                <input type="number" name="pareja1[]" id="" class="partido_input" min="0">
                            </div>

                            <div class="div-input">
                                <p>Set 2</p>
                                <input type="number" name="pareja1[]" id="" class="partido_input" min="0">
                            </div>

                            <div class="div-input">
                                <p>Set 3</p>
                                <input type="number" name="pareja1[]" id="" class="partido_input" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="lineaVertical"></div>
                    <div class="parejas_pareja pareja2">
                        <div class="pareja_nombre">
                            @foreach($jugadores as $index => $jugador)
                            @if ($index >= $jugadoresPorPareja)
                            <p>{{ $jugador->name }}</p>
                            @endif
                            @endforeach
                        </div>

                        <div class="pareja_div-inputs">
                            <div class="div-input">
                                <p>Set 1</p>
                                <input type="number" name="pareja2[]" id="" class="partido_input" min="0">
                            </div>

                            <div class="div-input">
                                <p>Set 2</p>
                                <input type="number" name="pareja2[]" id="" class="partido_input" min="0">
                            </div>

                            <div class="div-input">
                                <p>Set 3</p>
                                <input type="number" name="pareja2[]" id="" class="partido_input" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divResultado_Btn">
                    <button type="submit">Enviar resultado</button>
                </div>
            </form>
        </div>

    </main>
</x-layoutLiga>