<x-layout :deportes="$deportes" :user="$user">
    <main class="main-inicio">
        <section class="section gris">
            <div class="section_left">
                <h1 class="section_titulo">Crear ligas deportivas</h1>
                <p class="section_texto"><strong>SportsLeagueManager</strong> es una plataforma en línea que te brinda
                    la posibilidad de
                    crear y gestionar ligas deportivas de manera sencilla y eficiente.
                    <br><strong>¿Qué hace que
                        SportsLeagueManager
                        sea especial? </strong>Aquí tienes algunos puntos destacados:
                </p>

                <details id="ver-mas" class="ver-mas-inicio">

                    <ul class="section_list">

                        <li class="section_option">
                            <strong>Personalización:</strong><span> Eres tú quien decide las reglas de tu liga. Puedes establecer
                                los puntos, las clasificaciones y otros parámetros según tus necesidades específicas.</span>
                        </li>

                        <li class="section_option">
                            <strong>Automatización:</strong> Olvídate de los complicados sorteos de calendario y la
                            generación de
                            clasificaciones. SportsLeagueManager se encarga de todo de manera totalmente automática.
                        </li>
                    </ul>

                    <summary class="titulo-ver-mas">Ver más</summary>

                </details>

                <ul class="section_list hidden-section">

                    <li class="section_option">
                        <strong>Personalización:</strong><span> Eres tú quien decide las reglas de tu liga. Puedes establecer
                            los puntos, las clasificaciones y otros parámetros según tus necesidades específicas.</span>
                    </li>

                    <li class="section_option">
                        <strong>Automatización:</strong> Olvídate de los complicados sorteos de calendario y la
                        generación de
                        clasificaciones. SportsLeagueManager se encarga de todo de manera totalmente automática.
                    </li>
                </ul>
            </div>

            <div class="section_right">
                <img class="section_img-right" src="{{asset('img/liga1.png')}}" alt="">
            </div>

        </section>

        <section class="section blanco section_numeros">
            <h2 class="section_titulo mapa_titulo">SportsLeagueManager</h2>
            <div class="container_numeros">

                <div class="numeros_info">
                    <h3 class="num">+200</h3>
                    <p>Ligas</p>
                </div>

                <div class="numeros_info">
                    <h3 class="num">+1.000</h3>
                    <p>Equipos</p>
                </div>

                <div class="numeros_info">
                    <h3 class="num">+10.000</h3>
                    <p>Partidos realizados</p>
                </div>

            </div>
        </section>

        <section class="section gris">
            <div class="section_right">
                <img class="section_img-left" src="{{asset('img/liga.webp')}}" alt="">
            </div>

            <div class="section_left">
                <h2 class="section_titulo">¿Cómo funciona? Es fácil</h2>

                <p class="section_texto"><strong>SportsLeagueManager</strong> es una plataforma en línea que te brinda
                    la posibilidad de
                    crear y gestionar ligas deportivas de manera sencilla y eficiente.
                    <br><strong>¿Qué hace que
                        SportsLeagueManager
                        sea especial? </strong>Aquí tienes algunos puntos destacados:
                </p>

                <details id="ver-mas" class="ver-mas-inicio">


                    <ul class="section_list">
                        <li class="section_option">
                            <strong>Crea tu liga:</strong> Regístrate en SportsLeagueManager y crea tu propia liga. Define
                            las reglas, elige
                            el deporte y personaliza los detalles.
                        </li>

                        <li class="section_option">
                            <strong>Invita a participantes:</strong> Invita a tus amigos, compañeros de equipo o cualquier
                            persona interesada
                            a unirse a tu liga. Pueden registrarse y participar de manera activa.
                        </li>

                        <li class="section_option">
                            <strong>Gestiona la liga:</strong> Desde el panel de control, podrás ver todas tus ligas y
                            realizar acciones como
                            consultar la clasificación, revisar el calendario y más.
                        </li>
                    </ul>

                    <summary class="titulo-ver-mas">Ver más</summary>

                </details>

                <ul class="section_list hidden-section">
                    <li class="section_option">
                        <strong>Crea tu liga:</strong> Regístrate en SportsLeagueManager y crea tu propia liga. Define
                        las reglas, elige
                        el deporte y personaliza los detalles.
                    </li>

                    <li class="section_option">
                        <strong>Invita a participantes:</strong> Invita a tus amigos, compañeros de equipo o cualquier
                        persona interesada
                        a unirse a tu liga. Pueden registrarse y participar de manera activa.
                    </li>

                    <li class="section_option">
                        <strong>Gestiona la liga:</strong> Desde el panel de control, podrás ver todas tus ligas y
                        realizar acciones como
                        consultar la clasificación, revisar el calendario y más.
                    </li>
                </ul>
            </div>
        </section>

        <section class="section blanco section-cards">
            <h2 class="section-cards_titulo">Suscripciones</h2>
            <div class="cards">
                @foreach ($productos as $index => $producto)
                <article class="card_suscripcion @if ($index == 2) resaltar @endif">
                    <form action="{{ route('compra.checkout') }}" method="GET">
                        @csrf
                        <div class="suscripcion_info">
                            <h3 class="suscripcion_nombre">{{ $producto->nombre }}</h3>
                            <h3 class="suscripcion_precio">{{ $producto->precio }}€</h3>
                            <h3 class="suscripcion_descripcion">{{ $producto->descripcion }}</h3>
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button type="submit" class="suscripcion_btn @if ($index == 2) resaltar-btn @endif">Proceder al Pago</button>
                        </div>
                    </form>
                </article>
                @endforeach

            </div>
        </section>

        @if (!empty($success))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{ $success }}</h2>
            </div>
        </div>
        @endif

    </main>
</x-layout>