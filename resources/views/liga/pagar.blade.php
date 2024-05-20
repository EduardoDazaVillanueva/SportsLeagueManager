<x-layoutLiga :liga="$liga" :user="$user">

    <form action="{{ route('liga.inscribirse', ['liga' => $liga->id, 'userId' => $user->id]) }}" method="POST">
        @csrf

        

        <button type="submit" class="crear-boton btn-unirse">PAGAR</button>
    </form>

</x-layoutLiga>