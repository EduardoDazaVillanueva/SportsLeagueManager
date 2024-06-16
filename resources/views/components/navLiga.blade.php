@props(['liga', 'user'])
<nav class="nav nav-liga" id="nav">

    <button class="menu_cerrar" onclick="menuMovilCerrar()"><i class="fas fa-times"></i></button>

    <a href="/" class="option_link"><img src="{{ asset('img/SportsLeagueManager-reverse.png') }}" alt="Logo de la web"
            class="logo logo-liga"></a>

    <ul class="nav_list-liga">
        <li><a href="/liga/{{ $liga->id }}">Inicio</a></li>
        <li><a href="/liga/{{ $liga->id }}/Clasificacion">Clasificación</a></li>
        <li><a href="/liga/{{ $liga->id }}/Jugadores">Jugadores</a></li>
        <li><a href="/liga/{{ $liga->id }}/Partidos">Partidos</a></li>
    </ul>

    <div class="container_foto-nav container_foto-nav-liga">

        <img class="foto-nav foto-nav-liga" src="{{ asset('storage/imagenes/' . auth()->user()->logo) }}" alt="Imagen de perfil del usuario">

        <li class="desplegable"><span class="nombre-user">{{ auth()->user()->name }}</span>
            <ul class="nav_list mover">

                <li><a href="/perfil/{{ auth()->id() }}">Mi perfil</a></li>
                <li>
                    <form action="/logout" method="post"> @csrf<button type="submit"
                            class="list_option inicio-sesion">Cerrar sesión</button></form>
                </li>

            </ul>
        </li>

    </div>

</nav>

<nav class="nav-movil" id="nav-movil">

    <button class="menu_cerrar" onclick="menuMovilCerrar()"><i class="fas fa-times"></i></button>

    <a href="/" class="option_link logo-movil"><img src="{{ asset('img/SportsLeagueManager-reverse.png') }}"
            alt="Logo de la web" class="logo"></a>

    <ul class="nav_list nav_list-movil">
        <li><a href="/liga/{{ $liga->id }}">Inicio</a></li>
        <li><a href="/liga/{{ $liga->id }}/Clasificacion">Clasificación</a></li>
        <li><a href="/liga/{{ $liga->id }}/Jugadores">Jugadores</a></li>
        <li><a href="/liga/{{ $liga->id }}/Partidos">Partidos</a></li>
    </ul>

    <div class="container_foto-nav container_foto-nav-movil">

        <img class="foto-nav" src="{{ asset('storage/imagenes/' . auth()->user()->logo) }}" alt="Imagen de perfil del usuario">

        <li class="desplegable"><span class="nombre-user">{{ auth()->user()->name }}</span>
            <ul class="nav_list mover">

                <li><a href="/perfil/{{ auth()->id() }}">Mi perfil</a></li>
                <li>
                    <form action="/logout" method="post"> @csrf<button type="submit"
                            class="list_option inicio-sesion">Cerrar sesión</button></form>
                </li>

            </ul>
        </li>

    </div>
</nav>


<nav class="movil">
    <a href="/" class="option_link"><img src="{{ asset('img/SportsLeagueManager-reverse.png') }}"
            alt="Logo de la web" class="logo"></a>
    <button onclick="menuMovilAbrir()"><i class="fas fa-bars menu_movil" id="menu_movil"></i></button>
</nav>
