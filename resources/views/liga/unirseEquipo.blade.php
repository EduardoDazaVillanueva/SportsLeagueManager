<x-layout :deportes="$deportes" :user="$user">
    <main class="main-invitar">
        <h1 class="login_titulo">Unirse al equipo</h1>
        <form class="form" action="{{route('liga.ConfrimarCodigoEquipo', ['liga' => $liga->id])}}" method="POST">
            @csrf
            <div class="flex-column">
                <label class="titulo-input">Código de invitación </label>
            </div>
            <div class="inputForm">
                <input type="text" class="input" name="codigo" placeholder="Introduce el código del equipo">
            </div>
            @error('codigo')
            <div class="error">
                {{ $message }}
            </div>
            @enderror

            <button class="button-submit" type="submit">Reenviar</button>
            </p>
        </form>
    </main>
</x-layout>