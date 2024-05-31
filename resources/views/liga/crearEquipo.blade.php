<x-layoutLiga :liga="$liga" :user="$user">

    <main class="main">
        <h1 class="main_titulo">Crear Equipo</h1>

        <form class="equipo-form" action="{{ route('liga.storeEquipo', ['liga' => $liga->id, 'userId' => $user->id]) }}" method="POST">


            <div class="container-crear_equipo">
                @csrf

                <div id="nombre-div">
                    <label for="nombre" class="crear-label">Nombre del equipo*</label>
                    <input type="text" name="nombre" id="nombre" class="crear-input" placeholder="Nombre del equipo">
                    @error('nombre')
                    <div class="error">
                        <p>Nombre no válido</p>
                    </div>
                    @enderror
                </div>

                <input type="hidden" name="creador" value="{{$user->id}}">

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>
    </main>

</x-layoutLiga>