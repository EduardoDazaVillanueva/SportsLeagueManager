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
                <p>Las jornadas se juegan el/los día/s: <strong>
                        @for ($i = 0; $i < count($liga->dia_jornada); $i++)
                            @if ($i == (count($liga->dia_jornada) -2) )
                            {{ $liga->dia_jornada[$i]}} y
                            @elseif ($i == (count($liga->dia_jornada) -1))
                            {{ $liga->dia_jornada[$i]}}.
                            @else
                            {{ $liga->dia_jornada[$i]}},
                            @endif
                            @endfor

                    </strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p><strong>Puntuación:</strong></p>
                    <div class="container-pnt">
                        <p>Si ganas el partido:<strong> {{$liga->pnts_ganar}}</strong></p>
                        <p>Si pierder el partido:<strong> {{$liga->pnts_perder}}</strong></p>
                        <p>Si empatas el partido:<strong> {{$liga->pnts_empate}}</strong></p>
                        <p>Juegos:<strong> {{$liga->pnts_juego}}</strong> por cada juego de diferencia</p>
                    </div>
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
                    <p><strong>Premios:</strong></p>
                    <p>El primero:<strong> 200€</strong></p>
                    <p>Del segundo al quinto:<strong> 50€</strong></p>
                </div>

                <div>
                    <p>La inscripción finaliza:<strong> {{$liga->fecha_fin_inscripcion}}</strong></p>
                    <p>La inscripción es de <strong> {{$liga->precio}} </strong></p>
                    <p>Para abonarla hable con el creador de la liga<strong> {{$organizador->name}}</strong> en el número <strong>{{$organizador->telefono}}</strong></p>
                </div>
            </div>
            <div class="liena"></div>
            @if ($organizador->id != $user->id && $esJugador == 0)
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