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

        </section>


        <section class="main_section2">
            <h2 class="section2_titulo">Filtro</h2>

            <h3 for="localidad" class="filtro_titulo">Localidades:</h3>

            <div class="container-filtro">

                {{-- Asegúrate de que el formulario contenga todos los checkboxes --}}
                <form action="{{ route('liga.ligaDeporte', ['deporte' => $deporteID]) }}" method="GET" id="form-filtro">
                    @foreach ($localidades as $localidad)
                    <div class="container-check">
                        <input type="checkbox" name="localidades[]" id="{{ $localidad }}" class="filtro_check" value="{{ $localidad }}" {{-- Mantener el checkbox marcado si ya está seleccionado --}} @if(is_array(request('localidades')) && in_array($localidad, request('localidades'))) checked @endif {{-- Enviar el formulario automáticamente al cambiar el checkbox --}} onchange="this.form.submit()">
                        <label for="{{ $localidad }}" class="filtro_label">{{ $localidad }}</label>
                    </div>
                    @endforeach
                </form>

            </div>


        </section>

    </main>
</x-layout>