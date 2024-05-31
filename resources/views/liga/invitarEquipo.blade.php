<x-layout :deportes="$deportes" :user="$user">
    <main class="main-invitar_equipo">
        <div>
            <h1 class="login_titulo">Invita a tus amigos a tu equipo</h1>
            <p class="desc_codigo">Enviale tu código a tus amigos para que puedan unirse</p>
        </div>
        <div class="codigo_div">
            <p id="codigoUnirse">{{$equipo->codigo_unirse}}</p>
            <button id="copyButton"><i class="fa-solid fa-copy"></i></button>
            <span id="copySuccess">Código copiado con éxito</span>
        </div>
    </main>

    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            var codigo = document.getElementById('codigoUnirse').innerText;

            var tempInput = document.createElement('input');
            tempInput.value = codigo;
            document.body.appendChild(tempInput);

            tempInput.select();
            document.execCommand('copy');

            document.body.removeChild(tempInput);

            var successMessage = document.getElementById('copySuccess');
            successMessage.style.display = 'block';

            setTimeout(function() {
                successMessage.style.display = 'none';
            }, 1000);
        });
    </script>
</x-layout>