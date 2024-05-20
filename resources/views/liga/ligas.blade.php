<x-layout :deportes="$deportes" :user="$user">
    <main class="main-grid">

        <h1 class="main_titulo">{{$nombreDeporte->nombre}}</h1>

        <section class="main_section1">

            @foreach ($ligas as $liga)
            <a href="/liga/{{$liga->id}}" class="section1_liga">
                <article>

                    <img class="liga_img" src="{{ asset('storage/imagenes/' . $liga['logo']) }}" alt="logo de la liga">

                    <div class="liga_info">
                        <h2 class="liga_nombre"> {{$liga["nombre"]}} </h2>

                        {{-- Obtener el número de jugadores de la liga actual --}}
                        @php
                        $numeroJugadores = $jugadores->has($liga->id)
                        ? $jugadores->get($liga->id)->count()
                        : 0;
                        @endphp

                        <p class="liga_localidad"> Jugadores: <strong>{{ $numeroJugadores }}</strong> </p>

                        <p class="liga_localidad"> {{$liga["fecha_inicio"]}} / {{$liga["fecha_final"]}} </p>
                        <p class="liga_localidad">Localidad: <strong> {{$liga["localidad"]}} </strong></p>
                        <p class="liga_sede">Sede: <strong> {{$liga["sede"]}} </strong></p>
                    </div>
                </article>
            </a>
            @endforeach

            <div class="nuevaLiga">
                <form method="get" action="/liga/crear/{{$deporteID}}">
                    <button type="submit">
                        <article class="section1_liga-crear">
                            <i class="fa-solid fa-plus"></i>
                        </article>
                    </button>
                </form>
            </div>

            <div class="pagination-links">
                {{ $ligas->links() }}
            </div>

        </section>


        <section class="main_section2">
            <h2 class="section2_titulo">Filtro</h2>

            <h3 class="filtro_titulo">Localidades:</h3>

            <div class="container-filtro">
                <form action="{{ route('liga.ligaDeporte', ['deporte' => $deporteID]) }}" method="GET" id="form-filtro">
                    @foreach ($localidades as $localidad)
                    <div class="container-check">
                        <input type="checkbox" name="localidades[]" id="{{ $localidad }}" class="filtro_check" value="{{ $localidad }}" @if(is_array(request('localidades')) && in_array($localidad, request('localidades'))) checked @endif onchange="this.form.submit()">
                        <label for="{{ $localidad }}" class="filtro_label">{{ $localidad }}</label>
                    </div>
                    @endforeach

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha inicio <i class="fa-solid fa-arrow-up"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaInicio" id="fechaInicio" min="{{ date('Y-m-d') }}" value="{{ request('fechaInicio') }}" onchange="this.form.submit()">
                    </div>

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha final <i class="fa-solid fa-arrow-down"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaFinal" id="fechaFinal" min="{{ date('Y-m-d') }}" value="{{ request('fechaFinal') }}" onchange="this.form.submit()">
                    </div>

                    <h3 class="filtro_titulo">Jugadores inscritos:</h3>

                    @php
                    $rangoJugadores = [
                    '-10',
                    '-20',
                    '-30',
                    '-40',
                    '-50',
                    '+50',
                    ];
                    @endphp

                    @foreach ($rangoJugadores as $rango)
                    <div class="container-check">
                        <input type="radio" name="rangoJugadores" id="{{ $rango }}" class="filtro_check" value="{{ $rango }}" @if(request('rangoJugadores')==$rango) checked @endif onchange="this.form.submit()">
                        <label for="{{ $rango }}" class="filtro_label">{{ $rango }}</label>
                    </div>
                    @endforeach
                </form>
            </div>



        </section>

    </main>
</x-layout>