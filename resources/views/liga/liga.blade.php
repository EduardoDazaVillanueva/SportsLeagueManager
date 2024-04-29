<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-infoLiga">

        <div class="container-titulo_infoLiga">
            <img src="{{asset('storage/imagenes/' . $liga->logo)}}" alt="" class="img_infoLiga">
            <h1 class="titulo_infoLiga">{{$liga->nombre}}</h1>
        </div>

        <div class="container-infoLiga">
            <div class="div-info-liga">
                <p>Localidad: <strong>{{$liga->localidad}}</strong></p>
                <p>Sede: <strong>{{$liga->sede}}</strong></p>
                <p><strong>{{$liga->fecha_inicio}} / {{$liga->fecha_final}}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <p><strong>{{count($jugadores)}} </strong>jugadores inscritos</p>
                <p>Las jornadas se juegan el/los día/s: <strong>{{$liga->dia_jornada}}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p>Si ganas el partido:<strong> {{$liga->pnts_ganar}}</strong></p>
                    <p>Si pierder el partido:<strong> {{$liga->pnts_perder}}</strong></p>
                    <p>Si empatas el partido:<strong> {{$liga->pnts_empate}}</strong></p>
                    <p>Juegos:<strong> {{$liga->pnts_juego}}</strong> por cada juego de diferencia</p>
                </div>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga div-info-liga-respnsabilidad">
                <p><strong>Responsabilidad del jugador</strong></p>
                <p>{{$liga->txt_responsabilidad}}</p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p>El primero:<strong> 200€</strong></p>
                    <p>Del segundo al quinto:<strong> 50€</strong></p>
                </div>

                <div>
                    <p>La inscripcion finaliza:<strong> {{$liga->fecha_fin_inscripcion}}</strong></p>
                    <p>La inscripción es de:<strong> 10€</strong></p>
                    <p>Para abonarla hable con el creador de la liga:<strong> Eduardo Daza</strong> en el número <strong>123456789</strong></p>
                </div>
            </div>
            <div class="liena"></div>
            @if ($organizadorUserID != $user->id && $esJugador == 0)
            <div class="div-info-liga">
                <form action="{{ route('liga.inscribirse', ['liga' => $liga->id, 'userId' => $user->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="crear-boton btn-unirse">INSCRIBIRSE</button>
                </form>
            </div>
            @endif
        </div>





    </main>
</x-layoutLiga>