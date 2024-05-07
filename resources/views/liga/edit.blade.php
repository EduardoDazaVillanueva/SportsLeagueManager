<x-layout :deportes="$deportes" :user="$user">
    <main class="main">
        <h1 class="main_titulo">Crear liga</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="crear-form" action="{{ route('ligas.update', ['liga' => $liga->id]) }}" method="POST" enctype="multipart/form-data">

            <div class="container-crear">

                @csrf
                @method('PUT')

                <div id="nombre-div">
                    <label for="nombre" class="crear-label">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" class="crear-input" placeholder="Nombre de la liga">
                </div>

                <div id="logo-div">
                    <label for="logo" class="crear-label">Logo</label>
                    <div class="file" onclick="acticonstInput()">
                        <i class="fa-solid fa-file"></i>
                        <input type="file" name="logo" class="input-hidden" onchange="checkFile(this)">
                        <span class="text_foto">Añadir foto</span>
                        <i class="fa-solid fa-check hidden"></i>
                    </div>
                </div>

                <div id="dia_jornada-div">
                    <label class="crear-label">Dia de la jornada *</label>

                    <div class="dia-check">
                        <label>Lunes</label>
                        <input type="checkbox" value="Lunes" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Martes</label>
                        <input type="checkbox" value="Martes" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Miércoles</label>
                        <input type="checkbox" value="Miércoles" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Jueves</label>
                        <input type="checkbox" value="Jueves" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Viernes</label>
                        <input type="checkbox" value="Viernes" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Sábado</label>
                        <input type="checkbox" value="Sábado" name="dia_jornada[]" class="crear-input">
                    </div>

                    <div class="dia-check">
                        <label>Domingo</label>
                        <input type="checkbox" value="Domingo" name="dia_jornada[]" class="crear-input">
                    </div>
                </div>

                <div id="pnts_ganar-div">
                    <label for="pnts_ganar" class="crear-label">Puntos por ganar *</label>
                    <input type="number" name="pnts_ganar" id="pnts_ganar" class="crear-input" min="0" placeholder="3">
                </div>

                <div id="pnts_perder-div">
                    <label for="pnts_perder" class="crear-label">Puntos por perder *</label>
                    <input type="number" name="pnts_perder" id="pnts_perder" class="crear-input" min="0" placeholder="0">
                </div>

                <div id="pnts_empate-div">
                    <label for="pnts_empate" class="crear-label">Puntos por empatar</label>
                    <input type="number" name="pnts_empate" id="pnts_empate" class="crear-input" min="0" placeholder="1">
                </div>

                <div id="pnts_juego-div">
                    <label for="pnts_juego" class="crear-label">Puntos por juegos de diferencia</label>
                    <input type="number" name="pnts_juego" id="pnts_juego" class="crear-input" min="0" placeholder="Juegos de diferencia">
                </div>

                <div id="txt_responsabilidad-div">
                    <label for="txt_responsabilidad" class="crear-label">Responsabilidades de los jugadores</label>
                    <textarea name="txt_responsabilidad" id="txt_responsabilidad" class="crear-txt" placeholder="Los jugadores deberán pagar las pistas..."></textarea>
                </div>

                <div id="premios-div">
                    <label class="crear-label">Premios</label>

                    <div class="container-premios">
                        <div class="premio-titulos">
                            <p>Posición/es premiada/s</p>
                            <input type="text" name="posicion" id="posicion" placeholder="1 al 5">
                        </div>

                        <div class="premio-input">
                            <p>Premios</p>
                            <input type="text" name="premio" id="premio" placeholder="50€">
                        </div>
                    </div>
                </div>

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>
    </main>
</x-layout>