<x-layout :deportes="$deportes">
    <main class="main-grid">

        <h1 class="main_titulo">{{$nombreDeporte->nombre}}</h1>

        <section class="main_section1">

            @foreach ($ligas as $liga)
            <a href="/liga/{{$liga->id}}">
                <article class="section1_liga">

                    <img class="liga_img" src="" alt="logo de la liga">

                    <div class="liga_info">
                        <h2 class="liga_nombre"> {{$liga["nombre"]}} </h2>
                        <p class="liga_localidad"> {{$liga["fecha_inicio"]}} / {{$liga["fecha_final"]}} </p>
                        <p class="liga_localidad">Localidad: <strong> {{$liga["localidad"]}} </strong></p>
                        <p class="liga_sede">Sede: <strong> {{$liga["sede"]}} </strong></p>
                    </div>
                </article>
            </a>
            @endforeach



            <form method="get"   action="/liga/crear/{{$deporteID}}">
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

                <div class="container-check">
                    <input type="checkbox" name="cadiz" id="cadiz" class="filtro_check">
                    <label for="cadiz" class="filtro_label">Cádiz</label>
                </div>

                <div class="container-check">
                    <input type="checkbox" name="sevilla" id="sevilla" class="filtro_check">
                    <label for="sevilla" class="filtro_label">Sevilla</label>
                </div>

                <div class="container-check">
                    <input type="checkbox" name="jaen" id="jaen" class="filtro_check">
                    <label for="jaen" class="filtro_label">Jaén</label>
                </div>

                <details id="ver-mas">

                    <div class="container-check">
                        <input type="checkbox" name="sevilla" id="sevilla" class="filtro_check">
                        <label for="sevilla" class="filtro_label">Sevilla</label>
                    </div>

                    <div class="container-check">
                        <input type="checkbox" name="jaen" id="jaen" class="filtro_check">
                        <label for="jaen" class="filtro_label">Jaén</label>
                    </div>

                    <summary id="titulo-ver-mas">Ver más</summary>

                </details>

            </div>

        </section>

    </main>
</x-layout>