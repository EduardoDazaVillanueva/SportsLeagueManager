<x-layout :deportes="$deportes" :user="$user">
    <main class="main-infoLiga">

        <div class="container-titulo_perfil">
            <img src="{{ asset('storage/imagenes/' . $user['logo']) }}" alt="" class="img_perfil">
            <h1 class="titulo_miPerfil">{{ $user['name'] }}</h1>
            <a href="/editar/{{$user->id}}"><i class="fa-solid fa-pen-to-square edit-user"></i></a>
        </div>

        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Datos personales</h2>
            <p>Correo: <strong>{{ $user['email'] }}</strong></p>
            <p>Teléfono: <strong>{{ $user['telefono'] }}</strong></p>
        </div>

        @if ($user->id == Auth()->id())

        @if($suscripcion != -1 && $suscripcion != -2 && $suscripcion != null)
        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Suscripción</h2>
            <p>Tu suscripción termina en <strong>{{$suscripcion}}</strong> días</p>
        </div>
        @endif

        @if($suscripcion == -1)
        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Suscripción</h2>
            <p>Tu suscripción a caducado, te toca <strong><a href="/#suscripcion">renovar</a></strong></p>
        </div>
        @endif

        @if($suscripcion == null)
        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Suscripción</h2>
            <p>No te has suscrito todavia <strong><a href="/#suscripcion">Suscribirte</a></strong></p>
        </div>
        @endif

        @if($suscripcion == -2)
        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Suscripción</h2>
            <p>Tu suscrpción no caduca <strong>NUNCA</strong></p>
        </div>
        @endif

        @endif

        @if ($ligas != null && !$ligas->isEmpty())
        <div class="ligas_perfil">
            <h2 class="main_titulo">Participas</h2>
            @foreach ($ligas as $liga)
            <div class="liga_perfil">
                <a href="/liga/{{$liga->id}}" class="section1_liga">
                    <article>

                        <img class="liga_img-perfil" src="{{ asset('storage/imagenes/' . $liga['logo']) }}" alt="logo de la liga">

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
            </div>
            @endforeach

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
        </div>
        @endif

        @if ($ligasPropias != null && !$ligasPropias->isEmpty())
        <h2 class="main_titulo">Organizas</h2>
        <div class="ligas_perfil">
            @foreach ($ligasPropias as $ligaPropia)
            <a href="/liga/{{$ligaPropia->id}}" class="section1_liga">
                <article>

                    <img class="liga_img-perfil" src="{{ asset('storage/imagenes/' . $ligaPropia['logo']) }}" alt="logo de la liga">

                    <div class="liga_info">
                        <h2 class="liga_nombre"> {{$ligaPropia["nombre"]}} </h2>

                        @php
                        $numeroJugadores = $jugadores->has($ligaPropia->id)
                        ? $jugadores->get($ligaPropia->id)->count()
                        : 0;
                        @endphp

                        <p class="liga_localidad"> Jugadores: <strong>{{ $numeroJugadores }}</strong> </p>

                        <p class="liga_localidad"> {{$ligaPropia["fecha_inicio"]}} / {{$ligaPropia["fecha_final"]}} </p>
                        <p class="liga_localidad">Localidad: <strong> {{$ligaPropia["localidad"]}} </strong></p>
                        <p class="liga_sede">Sede: <strong> {{$ligaPropia["sede"]}} </strong></p>
                    </div>
                </article>
            </a>
            @endforeach

            <div class="pagination-links">
                <a href="{{ $ligasPropias->previousPageUrl() }}" class="pagination-link">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="pagination-numbers">
                    @foreach ($ligasPropias->getUrlRange(1, $ligasPropias->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pagination-link @if ($ligasPropias->currentPage() == $page) current-page @endif">{{ $page }}</a>
                    @endforeach
                </div>

                <a href="{{ $ligasPropias->nextPageUrl() }}" class="pagination-link">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
        @endif


    </main>


</x-layout>