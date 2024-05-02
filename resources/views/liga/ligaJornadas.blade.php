<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-jugadores">
        <form action="{{ route('liga.jornadas', ['liga' => $liga->id]) }}" method="GET">
            <select name="num_jornada" id="jornada_select" onchange="this.form.submit()">
                @foreach ($jornadas as $jornada)
                    <option value="{{ $jornada->num_jornada }}" 
                            {{ $numJornada == $jornada->num_jornada ? 'selected' : '' }}>
                        Jornada {{ $jornada->num_jornada }}
                    </option>
                @endforeach
            </select>
        </form>

        @foreach ($partidos as $partido)
            <div class="partido">
                Partido: {{ $partido->fecha }}
            </div>
        @endforeach
    </main>
</x-layoutLiga>
