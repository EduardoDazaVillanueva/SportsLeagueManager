<x-layout :deportes="$deportes" :user="$user">
    <main class="main">
        <h1 class="main_titulo">Editar perfil</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="crear-form" action="{{ route('user.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">

            <div class="container-edit_user">

                @csrf
                @method('PUT')

                <div id="nombre-div_user">
                    <label for="name" class="crear-label">Nombre</label>
                    <input type="text" name="name" id="name" class="crear-input" placeholder="Nombre del usuario" value="{{$user->name}}">
                </div>

                <div id="logo-div_user">
                    <label for="logo" class="crear-label">Logo</label>
                    <div class="file" onclick="acticonstInput()">
                        <i class="fa-solid fa-file"></i>
                        <input type="file" name="logo" class="input-hidden" onchange="checkFile(this)" value="{{$user->logo}}">
                        <span class="text_foto">Añadir foto</span>
                        <i class="fa-solid fa-check hidden"></i>
                    </div>
                </div>

                <div id="telefono-div_user">
                    <label for="telefono" class="crear-label">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" class="crear-input" placeholder="telefono" value="{{$user->telefono}}">
                </div>

                <button type="submit" class="crear-boton">Enviar</button>
            </div>
        </form>

        @if (session('error'))
        <div class="w-100">
            <div class="alerta envioEmail" id="alerta">
                <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
                <h2 class="alerta-email_titulo">{{session('error')}}</h2>
            </div>
        </div>
        @endif
    </main>
</x-layout>