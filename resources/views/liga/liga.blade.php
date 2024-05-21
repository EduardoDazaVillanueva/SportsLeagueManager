<x-layoutLiga :liga="$liga" :user="$user">
    <main class="main-infoLiga">

        <div class="container-titulo_infoLiga">
            <img src="{{asset('storage/imagenes/' . $liga->logo)}}" alt="" class="img_infoLiga">
            <h1 class="titulo_infoLiga">{{$liga->nombre}}</h1>
        </div>

        <div class="container-infoLiga">

            @if ($organizador->id == $user->id)
            <a href="/liga/editar/{{$liga->id}}"><i class="fa-solid fa-pen-to-square edit"></i></a>
            <a href="/liga/invitar/{{$liga->id}}"><i class="fa-solid fa-share-nodes share-own"></i></a>
            @else
            <a href="/liga/invitar/{{$liga->id}}"><i class="fa-solid fa-share-nodes share"></i></a>
            @endif

            <div class="div-info-liga">
                <p>Localidad: <strong>{{$liga->localidad}}</strong></p>
                <p>Sede: <strong>{{$liga->sede}}</strong></p>
                <p><strong>{{$liga->fecha_inicio}} / {{$liga->fecha_final}}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <p><strong> {{count($jugadores)}} </strong>jugadores inscritos</p>
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

                <p><strong>Desde las {{ substr($liga->primera_hora, 0, 5) }} hasta las {{ substr($liga->ultima_hora, 0, 5) }}</strong></p>

            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p><strong>Puntuación:</strong></p>
                    <div class="container-pnt">
                        <p>Si ganas el partido:<strong> {{$liga->pnts_ganar}}</strong></p>
                        <p>Si pierder el partido:<strong> {{$liga->pnts_perder}}</strong></p>
                        @if ($liga->pnts_empate > 0)
                        <p>Si empatas el partido:<strong> {{$liga->pnts_empate}}</strong></p>
                        @endif
                        @if ($liga->pnts_juego > 0)
                        <p><strong>{{$liga->pnts_juego}}</strong> por cada juego de diferencia</p>
                        @endif
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
                    <p><strong>Inscripción:</strong></p>
                    @if ($mostrarBotonInscribirse)
                    <p>La inscripción finaliza el día:<strong> {{$liga->fecha_fin_inscripcion}}</strong></p>
                    @else
                    <p>La inscripción finalizó el día:<strong> {{$liga->fecha_fin_inscripcion}}</strong></p>
                    @endif
                    @if ($liga->precio > 0)
                    <p>La inscripción es de <strong> {{$liga->precio}}€ </strong></p>
                    <p>Para abonarla hable con el creador de la liga<strong> {{$organizador->name}}</strong> en el número <strong>{{$organizador->telefono}}</strong></p>
                    @else
                    <p>La inscripción es <strong> Gratis </strong></p>
                    @endif
                </div>
            </div>
            @if ($organizador->id != $user->id && $esJugador == 0)
            <div class="liena"></div>
            <div class="{{$mostrarBotonInscribirse ? 'div-info-liga' : 'hidden'}}">

                @if ($liga->deporte_id == 3 || $liga->deporte_id == 4)
                <a class="crear-boton btn-unirse" href="{{ route('checkout.session') }}">INSCRIBIRSE</a>
                @else
                <a class="crear-boton btn-unirse" href="{{route('liga.crearEquipo', ['liga' => $liga->id])}}">INSCRIBIRSE</a>
                @endif

            </div>
            @endif
        </div>

        @if (!$juegaJornada && $organizador->id != $user->id && $esJugador == 1)
        <div class=" {{$mostrarDivRango ? 'alerta' : 'hidden'}}" id="alerta">
            <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
            <h2 class="alerta_titulo">Apuntate a la próxima jornada ({{$fechaJornada}})</h2>

            <form action="{{ route('liga.jugarJornada', ['liga' => $liga->id, 'userId' => $user->id]) }}" method="POST">
                <div class="alerta_div-form">
                    @for ($i = 0; $i < count($liga->dia_jornada); $i++)

                        @csrf

                        <div class="alerta_div-input">

                            @php
                            $checkboxId = 'dia_jornada_' . $liga->dia_jornada[$i];
                            @endphp

                            <label for="{{ $checkboxId }}">{{ $liga->dia_jornada[$i] }}</label>
                            <input type="checkbox" name="dia_jornada[]" class="alerta_input" value="{{ $liga->dia_jornada[$i] }}" id="{{ $checkboxId }}">
                        </div>
                        @endfor
                </div>

                <div class="alerta_div-btn">
                    <button type="submit" class="alerta_btn" id="alerta_btn">Enviar</button>
                </div>
            </form>
        </div>
        @endif

        @if (session('error'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('error')}}</h2>
            </div>
        </div>
        @endif
    </main>

</x-layoutLiga>