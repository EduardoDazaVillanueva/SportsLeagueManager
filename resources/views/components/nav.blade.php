@props(['deportes', 'user'])
<nav class="nav" id="nav">

    <button class="menu_cerrar" onclick="menuMovilCerrar()"><i class="fas fa-times"></i></button>

    <a href="/" class="option_link"><img src="{{asset('img/SportsLeagueManager-reverse.png')}}" alt="Logo de la web" class="logo"></a>

    <ul class="nav_list menu_lista">
        <li><a href="/">Inicio</a></li>
        <li class="desplegable">deportes
            <ul class="nav_list">

                @foreach ($deportes as $deporte)
                <li><a href="/liga/deporte/{{$deporte["id"]}}">{{$deporte["nombre"]}}</a></li>
                @endforeach

            </ul>
        </li>
        <li><a href="/faq">faq</a></li>
    </ul>

    @auth
    <div class="container_foto-nav">

        <img class="foto-nav" src="{{ asset('storage/imagenes/' . auth()->user()->logo) }}" alt="">

        <li class="desplegable"><span class="nombre-user">{{auth()->user()->name}}</span>
            <ul class="nav_list mover">

                <li><a href="/perfil/{{auth()->id()}}">Mi perfil</a></li>
                <li>
                    <form action="/logout" method="post"> @csrf<button type="submit" class="list_option inicio-sesion">Cerrar sesión</button></form>
                </li>

            </ul>
        </li>

    </div>
    @else
    <a href="/login" class="list_option inicio-sesion-login">Iniciar sesión</a>
    @endauth
</nav>

<nav class="movil">
    <a href="/" class="option_link"><img src="{{asset('img/SportsLeagueManager-reverse.png')}}" alt="Logo de la web" class="logo"></a>
    <button onclick="menuMovilAbrir()"><i class="fas fa-bars menu_movil" id="menu_movil"></i></button>
</nav>