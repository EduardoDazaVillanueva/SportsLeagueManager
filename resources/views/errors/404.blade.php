<x-layout :deportes="$deportes" :user="$user">
    <main class="main-404">
        <section class="error-section">
            <div class="container">
                <div class="content-404">
                    <h1 class="error-title">Error 404</h1>
                    <p class="error-message">Lo sentimos, pero la página que estás buscando no existe.</p>
                    <p class="error-suggestion">Por favor, verifica la URL o vuelve a la página de inicio.</p>

                    <div class="navigation">
                        <a href="{{ route('welcome') }}" class="button">Ir a la Página de Inicio</a>
                        <a href="{{ route('faq') }}" class="button">Ver Preguntas Frecuentes</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layout>