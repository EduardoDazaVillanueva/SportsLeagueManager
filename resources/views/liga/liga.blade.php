<x-layout :liga="$liga">
    <main class="main-infoLiga">

        <div class="container-titulo_infoLiga">
            <img src="../images/icono-sinFondo.png" alt="" class="img_infoLiga">
            <h1 class="titulo_infoLiga">{{$liga->nombre }}</h1>
        </div>

        <div class="container-infoLiga">
            <div class="div-info-liga">
                <p>Sede: <strong>{{$liga->sede }}</strong></p>
                <p><strong>{{$liga->fecha_inicio }} - {{$liga->fecha_final }}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <p><strong>50 </strong>jugadores inscritos</p>
                <p>Las jornadas se juegan el/los día/s: <strong>{{$liga->dia_jornada }}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p>Si ganas el partido:<strong> {{$liga->pnts_ganar }}</strong></p>
                    <p>Si pierder el partido:<strong> {{$liga->pnts_perder }}</strong></p>
                    <p>Si empatas el partido:<strong> {{$liga->pnts_empate }}</strong></p>
                    <p>Juegos:<strong> {{$liga->pnts_juego }}</strong> por cada juego de diferencia</p>
                </div>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <p><strong>{{$liga->txt_responsabilidad }}</strong></p>
            </div>
            <div class="liena"></div>

            <div class="div-info-liga">
                <div>
                    <p>El primero:<strong> 200€</strong></p>
                    <p>Del segundo al quinto:<strong> 50€</strong></p>
                </div>

                <div>
                    <p>La inscripcion finaliza:<strong> {{$liga->fecha_fin_inscripcion }}</strong></p>
                    <p>La inscripción es de:<strong> 10€</strong></p>
                    <p>Para abonarla hable con el creador de la liga:<strong> Eduardo Daza</strong> en el número <strong>123456789</strong></p>
                </div>
            </div>
            <div class="liena"></div>
        </div>
    </main>
</x-layout>