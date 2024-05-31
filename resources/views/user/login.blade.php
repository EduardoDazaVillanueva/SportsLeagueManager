<x-layout :deportes="$deportes" :user="$user">
    <main class="main-login">
        <h1 class="login_titulo">Iniciar sesión</h1>
        <form class="form" method="post" action="/login">
            @csrf
            <div class="flex-column">
                <label class="titulo-input">Correo electrónico </label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-at"></i>
                <input type="email" name="email" class="input" placeholder="Introduce tu correo electrónico">
            </div>
            @error('email')
            <div class="error">
                <p>Correo no válido</p>
            </div>
            @enderror

            <div class="flex-column">
                <label class="titulo-input">Contraseña </label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" class="input" placeholder="Introduce la contraseña">
            </div>
            @error('password')
            <div class="error">
                <p>Contraseña incorrecta</p>
            </div>
            @enderror

            <div class="flex-row">
                <a href=""><span class="span">¿Olvidaste la contraseña?</span></a>
            </div>
            <button class="button-submit" type="submit">Iniciar sesión</button>
            <p class="p">¿No tienes cuenta? <a href="{{route('registro')}}"><span class="span">Crear cuenta</span></a>

            </p>
        </form>
    </main>

    @if (session('success'))
    <div class="w-100">
        <div class="alerta envioEmail" id="alerta">
            <i class="fa-solid fa-xmark alerta_salir" onclick="cerrar()"></i>
            <h2 class="alerta-email_titulo">{{session('success')}}</h2>
        </div>
    </div>
    @endif

    <form id="resend-form" action="{{ route('reenviarCorreo') }}" method="post" style="display: none;">
        @csrf
    </form>


</x-layout>