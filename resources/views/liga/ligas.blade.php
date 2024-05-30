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
                <a href="{{ $ligas->previousPageUrl() }}" class="pagination-link">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="pagination-numbers">
                    @foreach ($ligas->getUrlRange(1, $ligas->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link @if ($ligas->currentPage() == $page) current-page @endif">{{ $page }}</a>
                    @endforeach
                </div>

                <a href="{{ $ligas->nextPageUrl() }}" class="pagination-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </section>


        <button class="filtrosBtn" onclick="filtroMovilAbrir()">Filtros<i class="fas fa-filter"></i></button>
        <section class="main_section2" id="main_section2">

            <button class="filtro_cerrar" onclick="filtroMovilCerrar()"><i class="fas fa-times"></i></button>

            <div class="section2_header">
                <h2 class="section2_titulo">Filtro</h2>

                <button class="section2_button" onclick="deseleccionarFiltros()">Borrar <i class="fa-solid fa-trash"></i></button>
            </div>


            <div class="container-filtro">
                <form action="{{ route('liga.ligaDeporte', ['deporte' => $deporteID]) }}" method="GET" id="form-filtro">
                    @php
                    $contador = 0
                    @endphp
                    <div>
                        <h3 class="filtro_titulo">Localidades:</h3>

                        <div class="container_localidades">
                            @foreach ($localidades as $localidad)

                            @if ($contador == 6)
                            <details class="details_verMas">
                                <summary class="verMas">Ver más</summary>
                                @endif

                                <div class="container-check">
                                    <input type="checkbox" name="localidades[]" id="{{ $localidad }}" class="filtro_check" value="{{ $localidad }}" @if(is_array(request('localidades')) && in_array($localidad, request('localidades'))) checked @endif onchange="this.form.submit()">
                                    <label for="{{ $localidad }}" class="filtro_label">{{ $localidad }}</label>
                                </div>
                                @php
                                $contador ++;
                                @endphp

                                @endforeach

                                @if ($contador >= 6)
                            </details>
                            @endif
                        </div>
                    </div>

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha inicio <i class="fa-solid fa-arrow-up"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaInicio" id="fechaInicio" min="{{ date('Y-m-d') }}" value="{{ request('fechaInicio') }}" onchange="this.form.submit()">
                    </div>

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha final <i class="fa-solid fa-arrow-down"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaFinal" id="fechaFinal" min="{{ date('Y-m-d') }}" value="{{ request('fechaFinal') }}" onchange="this.form.submit()">
                    </div>

                    <div>
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

                        <div class="container_rangos">
                            @foreach ($rangoJugadores as $rango)
                            <div class="container-check">
                                <input type="radio" name="rangoJugadores" id="{{ $rango }}" class="filtro_check" value="{{ $rango }}" @if(request('rangoJugadores')==$rango) checked @endif onchange="this.form.submit()">
                                <label for="{{ $rango }}" class="filtro_label">{{ $rango }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="main_section2-movil" id="main_section2-movil">

            <button class="filtro_cerrar" onclick="filtroMovilCerrar()"><i class="fas fa-times"></i></button>

            <div class="section2_header">
                <h2 class="section2_titulo">Filtro</h2>

                <button class="section2_button" onclick="deseleccionarFiltros()">Borrar <i class="fa-solid fa-trash"></i></button>
            </div>


            <div class="container-filtro">
                <form action="{{ route('liga.ligaDeporte', ['deporte' => $deporteID]) }}" method="GET" id="form-filtro">
                    @php
                    $contador = 0
                    @endphp
                    <div>
                        <h3 class="filtro_titulo">Localidades:</h3>

                        <div class="container_localidades">
                            @foreach ($localidades as $localidad)

                            @if ($contador == 6)
                            <details class="details_verMas">
                                <summary class="verMas">Ver más</summary>
                                @endif

                                <div class="container-check">
                                    <input type="checkbox" name="localidades[]" id="{{ $localidad }}" class="filtro_check" value="{{ $localidad }}" @if(is_array(request('localidades')) && in_array($localidad, request('localidades'))) checked @endif onchange="this.form.submit()">
                                    <label for="{{ $localidad }}" class="filtro_label">{{ $localidad }}</label>
                                </div>
                                @php
                                $contador ++;
                                @endphp

                                @endforeach

                                @if ($contador >= 6)
                            </details>
                            @endif
                        </div>
                    </div>

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha inicio <i class="fa-solid fa-arrow-up"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaInicio" id="fechaInicio" min="{{ date('Y-m-d') }}" value="{{ request('fechaInicio') }}" onchange="this.form.submit()">
                    </div>

                    <div class="filtro-fecha_div">
                        <h3 class="filtro_titulo">Fecha final <i class="fa-solid fa-arrow-down"></i></h3>
                        <input type="date" class="filtro-fecha" name="fechaFinal" id="fechaFinal" min="{{ date('Y-m-d') }}" value="{{ request('fechaFinal') }}" onchange="this.form.submit()">
                    </div>

                    <div>
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

                        <div class="container_rangos">
                            @foreach ($rangoJugadores as $rango)
                            <div class="container-check">
                                <input type="radio" name="rangoJugadores" id="{{ $rango }}" class="filtro_check" value="{{ $rango }}" @if(request('rangoJugadores')==$rango) checked @endif onchange="this.form.submit()">
                                <label for="{{ $rango }}" class="filtro_label">{{ $rango }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </section>

        @if (session('error'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('error')}}</h2>
            </div>
        </div>
        @endif

    </main>
</x-layout>