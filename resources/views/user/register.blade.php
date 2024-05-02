<x-layout :deportes="$deportes" :user="$user">
    <main class="main-login">
        <h1 class="login_titulo">Crear cuenta</h1>
        <form action="{{route('validar-register')}}" method="POST" enctype="multipart/form-data" class="form">
            @csrf
            <div class="flex-column">
                <label class="titulo-input">Nombre completo</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="name" class="input" placeholder="Introduce tu nombre">
            </div>
            @error('name')
            <div class="error">
                <p>El nombre ya está en uso</p>
            </div>
            @enderror

            <div class="flex-column">
                <label class="titulo-input">Añadir foto de perfil</label>
            </div>
            <div class="file" onclick="acticonstInput()">
                <i class="fa-solid fa-file"></i>
                <input type="file" name="logo" class="input-hidden" onchange="checkFile(this)">
                Añadir foto
                <i class="fa-solid fa-check hidden"></i>
            </div>
            @error('logo')
            <div class="error">
                <p>Archivo no válido</p>
            </div>
            @enderror

            <div class="flex-column">
                <label class="titulo-input">Teléfono</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-phone"></i>
                <input type="tel" name="telefono" class="input" placeholder="Introduce tu número de teléfono">
            </div>
            @error('telefono')
            <div class="error">
                <p>Teléfono incorrecto</p>
            </div>
            @enderror

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

            <div class="flex-column">
                <label class="titulo-input">Verificar contraseña</label>
            </div>
            <div class="inputForm">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password_confirmation" class="input" placeholder="Confirma tu contraseña">
            </div>
            
            <button class="button-submit" type="submit">Crear cuenta</button>
            <p class="p">¿Ya tienes cuenta? <a href="/login"><span class="span">Inicia sesión</span></a>
        </form>
    </main>
</x-layout>
