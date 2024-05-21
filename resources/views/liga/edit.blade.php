<x-layout :deportes="$deportes" :user="$user">
    <main class="main">
        <h1 class="main_titulo">Editar liga</h1>

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

            <div class="container-edit">

                @csrf
                @method('PUT')

                <div id="nombre-div">
                    <label for="nombre" class="crear-label">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" class="crear-input" placeholder="Nombre de la liga" value="{{$liga->nombre}}">
                </div>

                <div id="logo-div">
                    <label for="logo" class="crear-label">Logo</label>
                    <div class="file" onclick="acticonstInput()">
                        <i class="fa-solid fa-file"></i>
                        <input type="file" name="logo" class="input-hidden" onchange="checkFile(this)" value="{{$liga->logo}}">
                        <span class="text_foto">Añadir foto</span>
                        <i class="fa-solid fa-check hidden"></i>
                    </div>
                </div>

                <div id="numPistas-div">
                    <label for="numPistas" class="crear-label">Pistas disponibles por turno *</label>
                    <input type="number" name="numPistas" id="numPistas" class="crear-input" min="1" placeholder="Pistas disponibles por turno" value="{{$liga->numPistas}}">
                    @error('numPistas')
                    <div class="error">
                        <p>Número de pistas no válido</p>
                    </div>
                    @enderror
                </div>

                <div id="pnts_ganar-div">
                    <label for="pnts_ganar" class="crear-label">Puntos por ganar *</label>
                    <input type="number" name="pnts_ganar" id="pnts_ganar" class="crear-input" min="0" placeholder="3" value="{{$liga->pnts_ganar}}">
                </div>

                <div id="pnts_perder-div">
                    <label for="pnts_perder" class="crear-label">Puntos por perder *</label>
                    <input type="number" name="pnts_perder" id="pnts_perder" class="crear-input" min="0" placeholder="0" value="{{$liga->pnts_perder}}">
                </div>

                <div id="pnts_empate-div">
                    <label for="pnts_empate" class="crear-label">Puntos por empatar</label>
                    <input type="number" name="pnts_empate" id="pnts_empate" class="crear-input" min="0" placeholder="1" value="{{$liga->pnts_empate}}">
                </div>

                <div id="pnts_juego-div">
                    <label for="pnts_juego" class="crear-label">Puntos por juegos de diferencia</label>
                    <input type="number" name="pnts_juego" id="pnts_juego" class="crear-input" min="0" placeholder="Juegos de diferencia" value="{{$liga->pnts_juego}}">
                </div>

                <div id="txt_responsabilidad-div">
                    <label for="txt_responsabilidad" class="crear-label">Responsabilidades de los jugadores</label>
                    <textarea name="txt_responsabilidad" id="txt_responsabilidad" class="crear-txt" placeholder="Los jugadores deberán pagar las pistas...">{{$liga->txt_responsabilidad}}</textarea>
                </div>

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>

        @if (session('error'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('error')}}</h2>
            </div>
        </div>
        @endif
    </main>
</x-layout>