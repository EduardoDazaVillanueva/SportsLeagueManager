<x-layout :deportes="$deportes" :user="$user">
    <main class="main">
        <h1 class="main_titulo">Crear liga</h1>

        <form class="crear-form" action="{{route('crearLiga')}}" method="POST" enctype="multipart/form-data">

            <div class="container-crear">

                @csrf

                <div id="nombre-div">
                    <label for="nombre" class="crear-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="crear-input">
                </div>

                <div id="logo-div">
                    <label for="logo" class="crear-label">Logo:</label>
                    <div class="file" onclick="activarInput()">
                        <i class="fa-solid fa-file"></i>
                        <input type="file" name="logo" class="input-hidden" onchange="checkFile(this)">
                        <span class="text_foto">Añadir foto</span>
                        <i class="fa-solid fa-check hidden"></i>
                    </div>
                </div>

                <div id="fecha_inicio-div">
                    <label for="fecha_inicio" class="crear-label">Fecha inicio de la liga:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="crear-input">
                </div>

                <div id="fecha_final-div">
                    <label for="fecha_final" class="crear-label">Fecha final de la liga:</label>
                    <input type="date" name="fecha_final" id="fecha_final" class="crear-input">
                </div>

                <div id="fecha_fin_inscripcion-div">
                    <label for="fecha_fin_inscripcion" class="crear-label">Fecha final de la inscripcion:</label>
                    <input type="date" name="fecha_fin_inscripcion" id="fecha_fin_inscripcion" class="crear-input">
                </div>

                <div id="localidad-div">
                    <label for="localidad" class="crear-label">Localidad:</label>
                    <input type="text" name="localidad" id="localidad" class="crear-input">
                </div>

                <div id="sede-div">
                    <label for="sede" class="crear-label">Sede:</label>
                    <input type="text" name="sede" id="sede" class="crear-input">
                </div>

                <div id="dia_jornada-div">
                    <label for="dia_jornada" class="crear-label">Dia de la jornada:</label>
                    <select name="dia_jornada" id="dia_jornada" class="crear-select">
                        <option value="1" class="crear-option">Lunes</option>
                        <option value="2" class="crear-option">Martes</option>
                        <option value="3" class="crear-option">Miercoles</option>
                        <option value="4" class="crear-option">Jueves</option>
                        <option value="5" class="crear-option">Viernes</option>
                        <option value="6" class="crear-option">Sábado</option>
                        <option value="7" class="crear-option">Domingo</option>
                        <option value="8" class="crear-option">Sábado y Domingo</option>
                        <option value="9" class="crear-option">Toda la semana</option>
                    </select>
                </div>

                <div id="pnts_ganar-div">
                    <label for="pnts_ganar" class="crear-label">Puntos por ganar:</label>
                    <input type="number" name="pnts_ganar" id="pnts_ganar" class="crear-input" min="0">
                </div>

                <div id="pnts_perder-div">
                    <label for="pnts_perder" class="crear-label">Puntos por perder:</label>
                    <input type="number" name="pnts_perder" id="pnts_perder" class="crear-input" min="0">
                </div>

                <div id="pnts_empate-div">
                    <label for="pnts_empate" class="crear-label">Puntos por empatar:</label>
                    <input type="number" name="pnts_empate" id="pnts_empate" class="crear-input" min="0">
                </div>

                <div id="pnts_juego-div">
                    <label for="pnts_juego" class="crear-label">Puntos por juego:</label>
                    <input type="number" name="pnts_juego" id="pnts_juego" class="crear-input" min="0">
                </div>

                <div id="inscripcion-div">
                    <label for="inscripcion" class="crear-label">Precio inscripción:</label>

                    <div id="container_checks">
                        <div class="div_check">
                            <input type="checkbox" name="gratis" id="gratis" value="gratis">
                            <label for="gratis">Gratis</label>
                        </div>
                        <div class="div_check">
                            <input type="checkbox" name="pago" id="pago" value="pago" onclick="toggleInscripcion()">
                            <label for="pago">Pago</label>
                        </div>
                    </div>

                    <input type="number" name="txt_inscripcion" id="txt_inscripcion" class="crear-input txt_inscripcion" placeholder="Indica el precio" min="1">

                </div>

                <div id="txt_responsabilidad-div">
                    <label for="txt_responsabilidad" class="crear-label">Responsabilidades:</label>
                    <textarea name="txt_responsabilidad" id="txt_responsabilidad" class="crear-txt"></textarea>
                </div>

                <input type="hidden" name="organizadores_id" value="{{$user->id}}">
                <input type="hidden" name="deporte_id" value="{{$deporteID}}">

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>
    </main>
</x-layout>