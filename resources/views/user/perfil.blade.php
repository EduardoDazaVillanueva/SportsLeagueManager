<x-layout :deportes="$deportes" :user="$user">
    <main class="main-infoLiga">

        <div class="container-titulo_perfil">
            <img src="{{ asset('storage/imagenes/' . $user['logo']) }}" alt="" class="img_perfil">
            <h1 class="titulo_miPerfil">{{$user['name']}}</h1>
        </div>

        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Datos personales</h2>
            <p>Fecha de nacimiento: <strong>04/10/2003</strong></p>
            <p>Correo: <strong>{{$user['email']}}</strong></p>
            <p>Teléfono: <strong>{{$user['telefono']}}</strong></p>
        </div>

        <div class="div_mis-ligas">
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
        </div>

    </main>


</x-layout>