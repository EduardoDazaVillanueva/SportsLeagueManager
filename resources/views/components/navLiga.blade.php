@props(['liga'])
<nav class="nav">
    <a href="/" class="option_link"><img src="{{asset('img/SportsLeagueManager-reverse.png')}}" alt="Logo de la web" class="logo"></a>

        <ul class="nav_list">
            <li><a href="{{$liga->id }}">Inicio</a></li>
            <li><a href="{{$liga->id }}/Clasificacion">Clasificación</a></li>
            <li><a href="{{$liga->id }}/Jugadores">Jugadores</a></li>
            <li><a href="{{$liga->id }}/Jornadas">Jornadas</a></li>
            <li><a href="{{$liga->id }}/Partidos">Partidos</a></li>
        </ul>
        <a href="{{$liga->id }}/Perfil">Perfil</a>
</nav>