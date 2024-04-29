@props(['liga', 'user'])
<nav class="nav">
    <a href="/" class="option_link"><img src="{{asset('img/SportsLeagueManager-reverse.png')}}" alt="Logo de la web" class="logo"></a>

    <ul class="nav_list">
        <li><a href="/liga/{{$liga->id}}">Inicio</a></li>
        <li><a href="/liga/{{$liga->id}}/Clasificacion">Clasificación</a></li>
        <li><a href="/liga/{{$liga->id}}/Jugadores">Jugadores</a></li>
        <li><a href="/liga/{{$liga->id}}/Jornadas">Jornadas</a></li>
        <li><a href="/liga/{{$liga->id}}/Partidos">Partidos</a></li>
    </ul>

    <div class="container_foto-nav">

        <img class="foto-nav" src="{{asset('storage/imagenes/' . $user->logo)}}" alt="">

        <li class="desplegable"><span class="nombre-user">{{$user->name}}</span>
            <ul class="nav_list mover">

                <li><a href="/liga/{{$liga->id}}/perfil">Mi perfil</a></li>
                <li>
                    <form action="/logout" method="post"> @csrf<button type="submit" class="list_option inicio-sesion">Cerrar sesión</button></form>
                </li>

            </ul>
        </li>

    </div>

</nav>