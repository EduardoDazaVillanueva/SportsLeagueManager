@props(['deportes'])
<nav class="nav">
    <a href="/" class="option_link"><img src="{{asset('img/SportsLeagueManager-reverse.png')}}" alt="Logo de la web" class="logo"></a>

        <ul class="nav_list">
            <li><a href="/">Inicio</a></li>
            <li class="desplegable">deportes
                <ul id="deportes" class="nav_list">

                    @foreach ($deportes as $deporte)
                    <li><a href="/liga/{{$deporte["id"]}}">{{$deporte["nombre"]}}</a></li>
                    @endforeach
                    
                </ul>
            </li>
            <li><a href="/faq">faq</a></li>
        </ul>
        
        @auth
        <form action="/logout" method="post"> @csrf<button type="submit" class="list_option inicio-sesion">Cerrar sesión</button></a>
        @else
        <a href="/login" class="list_option inicio-sesion">Iniciar sesión</a>
        @endauth
</nav>