<!DOCTYPE html>
<html>

<head>
    <title>PDF Template</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .jugadores_tabla {
            text-align: center;

            background-color: #fff;

            border-collapse: collapse;
        }

        th,
        td {
            vertical-align: middle;
        }

        .tabla_titulo {
            padding: 5px;

            font-size: 12px;
            text-transform: uppercase;

            text-align: center;
        }

        tbody {
            display: inline-block;

            background-color: #fff;

            font-weight: bold;

            border-radius: 5px;
        }


        .tabla_datos {
            font-size: 12px;

            color: #333;
        }

        .tabla_dato {
            padding: 5px;
        }

        .tabla_cabecera {
            background-color: #040738;
            color: #c6cf25;
        }

        .gris{
            background-color: #eee;
        }

        .email_titulo{
            color: #040738;
            font-size: 20px;

            margin-bottom: 10px;
        }

        .email_subtitulo{
            color: #040738;
            font-size: 15px;
        }

        .resalto{
            color: #c6cf25;
        }
    </style>
</head>

<body>
    <div>
        <h1 class="email_titulo">La liga {{$liga->nombre}} ha finalizado</h1>
        <h2 class="email_subtitulo">Así ha terminado la clasificación</h2>
    </div>

    <table class="jugadores_tabla">
        <tr class="tabla_cabecera">
            <th class="tabla_titulo">Posición</th>
            <th class="tabla_titulo">Nombre</th>
            <th class="tabla_titulo">Partidos jugados</th>
            <th class="tabla_titulo">Partidos ganados</th>
            <th class="tabla_titulo">Partidos empatados</th>
            <th class="tabla_titulo">Partidos perdidos</th>
            <th class="tabla_titulo">Puntos</th>
        </tr>


        @php
        $posicion = 1;
        @endphp

        @foreach ($jugadores as $index => $jugador)

        <tr class="tabla_datos {{$index % 2 ? 'gris' : ''}}">
            <td class="tabla_dato">{{$posicion}}</td>
            <td class="tabla_dato @if ($jugador["user_name"] == $user->name)
            resalto
            @endif">{{$jugador["user_name"]}}</td>
            <td class="tabla_dato">{{$jugador["num_partidos"]}}</td>
            <td class="tabla_dato">{{$jugador["num_partidos_ganados"]}}</td>
            <td class="tabla_dato">{{$jugador["num_partidos_empatados"]}}</td>
            <td class="tabla_dato">{{$jugador["num_partidos_perdidos"]}}</td>
            <td class="tabla_dato">{{$jugador["puntos"]}}</td>
        </tr>

        @php
        $posicion++;
        @endphp

        @endforeach
    </table>
</body>

</html>