<x-layoutLiga :liga="$liga" :user="$user">

    <form action="{{ route('liga.storeEquipo', ['liga' => $liga->id, 'userId' => $user->id]) }}" method="POST">
        @csrf

        @php
        $deporteId = $liga->deporte_id;

        if($deporteId == 1){
        $contador = 11;
        }elseif($deporteId == 2){
        $contador = 5;
        }elseif($deporteId == 5){
        $contador = 15;
        }
        @endphp

        @for ($i = 0; $i < $contador; $i++) <label for="jugador{{$i + 1}}">Jugador {{$i +1}}</label>
            <input type="text" name="jugador{{$i + 1}}" id="jugador{{$i + 1}}">

            @endfor

            <button type="submit" class="crear-boton btn-unirse">CREAR</button>
    </form>

</x-layoutLiga>