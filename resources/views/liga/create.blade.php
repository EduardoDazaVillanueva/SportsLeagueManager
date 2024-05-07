<x-layout :deportes="$deportes" :user="$user">
    <main class="main">
        <h1 class="main_titulo">Crear liga</h1>

        <form class="crear-form" action="{{route('crearLiga')}}" method="POST" enctype="multipart/form-data">

            <div class="container-crear">

                @csrf

                <div id="nombre-div">
                    <label for="nombre" class="crear-label">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" class="crear-input" placeholder="Nombre de la liga">
                    @error('nombre')
                    <div class="error">
                        <p>Nombre no válido</p>
                    </div>
                    @enderror
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

                <div id="fecha_inicio-div">
                    <label for="fecha_inicio" class="crear-label">Fecha inicio de la liga *</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="crear-input" min="<?= date('Y-m-d') ?>">
                    @error('fecha_inicio')
                    <div class="error">
                        <p>La fecha debe ser anterior a la fecha de fin.</p>
                    </div>
                    @enderror
                </div>

                <div id="fecha_final-div">
                    <label for="fecha_final" class="crear-label">Fecha final de la liga *</label>
                    <input type="date" name="fecha_final" id="fecha_final" class="crear-input" min="<?= date('Y-m-d') ?>">
                </div>

                <div id="fecha_fin_inscripcion-div">
                    <label for="fecha_fin_inscripcion" class="crear-label">Fecha final de la inscripcion *</label>
                    <input type="date" name="fecha_fin_inscripcion" id="fecha_fin_inscripcion" class="crear-input" min="<?= date('Y-m-d') ?>">
                    @error('fecha_fin_inscripcion')
                    <div class="error">
                        <p>La fecha debe ser anterior a la fecha de inicio.</p>
                    </div>
                    @enderror
                </div>

                <div id="localidad-div">
                    <label for="localidad" class="crear-label">Localidad *</label>
                    <input type="text" name="localidad" id="localidad" class="crear-input" placeholder="Localidad">
                    @error('localidad')
                    <div class="error">
                        <p>Localidad no válida</p>
                    </div>
                    @enderror
                </div>

                <div id="sede-div">
                    <label for="sede" class="crear-label">Sede *</label>
                    <input type="text" name="sede" id="sede" class="crear-input" placeholder="donde se realiza la liga">
                    @error('sede')
                    <div class="error">
                        <p>Sede no válida</p>
                    </div>
                    @enderror
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
                    @error('dia_jornada')
                    <div class="error">
                        <p>Día no válido</p>
                    </div>
                    @enderror
                </div>

                <div id="pnts_ganar-div">
                    <label for="pnts_ganar" class="crear-label">Puntos por ganar *</label>
                    <input type="number" name="pnts_ganar" id="pnts_ganar" class="crear-input" min="0" placeholder="3">
                    @error('pnts_ganar')
                    <div class="error">
                        <p>Puntos no válidos</p>
                    </div>
                    @enderror
                </div>

                <div id="pnts_perder-div">
                    <label for="pnts_perder" class="crear-label">Puntos por perder *</label>
                    <input type="number" name="pnts_perder" id="pnts_perder" class="crear-input" min="0" placeholder="0">
                    @error('pnts_perder')
                    <div class="error">
                        <p>Puntos no válidos</p>
                    </div>
                    @enderror
                </div>

                <div id="pnts_empate-div">
                    <label for="pnts_empate" class="crear-label">Puntos por empatar</label>
                    <input type="number" name="pnts_empate" id="pnts_empate" class="crear-input" min="0" placeholder="1">
                </div>

                @if ($deporteID == 3 || $deporteID == 4 || $deporteID == 5)
                <div id="pnts_juego-div">
                    <label for="pnts_juego" class="crear-label">Puntos por juegos de diferencia</label>
                    <input type="number" name="pnts_juego" id="pnts_juego" class="crear-input" min="0" placeholder="Juegos de diferencia">
                </div>
                @endif

                <div id="inscripcion-div">
                    <label for="inscripcion" class="crear-label">Precio inscripción *</label>

                    <div id="container_checks">
                        <div class="div_check">
                            <input type="radio" name="inscripcion" id="gratis" value="gratis" onclick="toggleInscripcion()">
                            <label for="gratis">Gratis</label>
                        </div>
                        <div class="div_check">
                            <input type="radio" name="inscripcion" id="pago" value="pago" onclick="toggleInscripcion()">
                            <label for="pago">Pago</label>
                        </div>
                    </div>

                    <div class="tooltip top"> 
                        <input type="number" name="precio" id="precio" class="crear-input txt_inscripcion" placeholder="Indica el precio" min="1">
                        <span class="tiptext">La web se queda un 20% del precio</span>
                    </div>


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

                <input type="hidden" name="organizadores_id" value="{{$user->id}}">
                <input type="hidden" name="deporte_id" value="{{$deporteID}}">

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>
    </main>
</x-layout>