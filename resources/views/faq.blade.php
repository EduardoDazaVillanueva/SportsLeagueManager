<x-layout :deportes="$deportes" :user="$user">
    <main class="main-preguntas">
        <div class="section_left">
            <h1 class="main_titulo">Preguntas frecuentes</h1>
        </div>

        <section class="section-preguntas">

            <article class="container-pregunta">
                <details>
                    <summary>¿Esto es una pregunta?</summary>
                    <p>Esto es la respuesta</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Esto es una pregunta?</summary>
                    <p>Esto es la respuesta</p>
                </details>
            </article>
        </section>
    </main>
</x-layout>