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
                        <p class="liga_localidad"> {{$liga["fecha_inicio"]}} / {{$liga["fecha_final"]}} </p>
                        <p class="liga_localidad">Localidad: <strong> {{$liga["localidad"]}} </strong></p>
                        <p class="liga_sede">Sede: <strong> {{$liga["sede"]}} </strong></p>
                    </div>
                </article>
            </a>
            @endforeach



            <form method="get" action="/liga/crear/{{$deporteID}}">
                <button type="submit">
                    <article class="section1_liga-crear">
                        <i class="fa-solid fa-plus"></i>
                    </article>
                </button>
            </form>


        </section>

        <section class="main_section2">
            <h2 class="section2_titulo">Filtro</h2>

            <h3 for="localidad" class="filtro_titulo">Localidades:</h3>
            <div class="container-filtro">

                @php
                $cantidad = 1;
                @endphp

                @foreach ($localidades as $localidad)

                @if ($cantidad <= 3) <div class="container-check">
                    <input type="checkbox" name="{{$localidad}}" id="{{$localidad}}" class="filtro_check">
                    <label for="{{$localidad}}" class="filtro_label">{{$localidad}}</label>
            </div>
            @endif

            @php
            $cantidad++;
            @endphp

            @endforeach

            @if ($cantidad > 3)

            @php
            $cantidad = 1;
            @endphp
            <details id="ver-mas">
                <summary id="titulo-ver-mas">Ver más</summary>
                @foreach ($localidades as $localidad)
                @if ($cantidad > 3)
                <div class="container-check">
                    <input type="checkbox" name="{{$localidad}}" id="{{$localidad}}" class="filtro_check">
                    <label for="{{$localidad}}" class="filtro_label">{{$localidad}}</label>
                </div>
                @endif

                @php
                $cantidad++;
                @endphp
                @endforeach
            </details>
            @endif
            </div>

        </section>

    </main>
</x-layout>