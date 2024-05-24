<x-layout :deportes="$deportes" :user="$user">
    <main class="main-infoLiga">

        <div class="container-titulo_perfil">
            <img src="{{ asset('storage/imagenes/' . $user['logo']) }}" alt="" class="img_perfil">
            <h1 class="titulo_miPerfil">{{ $user['name'] }}</h1>
        </div>

        <div class="div-info-perfil">
            <h2 class="nombre_miPerfil">Datos personales</h2>
            <p>Correo: <strong>{{ $user['email'] }}</strong></p>
            <p>Teléfono: <strong>{{ $user['telefono'] }}</strong></p>
        </div>

    </main>


</x-layout>