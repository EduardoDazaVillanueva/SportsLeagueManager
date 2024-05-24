<x-layout :deportes="$deportes" :user="$user">
    <main class="main-preguntas">
        <div class="section_left">
            <h1 class="main_titulo">Preguntas frecuentes</h1>
        </div>

        <section class="section-preguntas">

            <article class="container-pregunta">
                <details>
                    <summary>¿Qué es SportsLeagueManager?</summary>
                    <p>Nuestra plataforma te permite organizar, gestionar y participar en ligas deportivas de cualquier disciplina. Ofrecemos herramientas para facilitar la administración de equipos, partidos y estadísticas.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Hay un límite en la cantidad de ligas que puedo crear?</summary>
                    <p>No, puedes crear tantas ligas como desees con tu suscripción mensual de 15€.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Cómo se gestionan las inscripciones en las ligas?</summary>
                    <p>El organizador de la liga decide si las inscripciones son gratuitas o de pago. Si decides cobrar una inscripción, recibirás el 80% del dinero recaudado.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Cómo puedo recibir el dinero de las inscripciones pagadas?</summary>
                    <p>Los organizadores recibirán sus pagos a través de la plataforma, que puede transferir los fondos a tu cuenta bancaria o a través de otros métodos de pago electrónicos.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Puedo personalizar las reglas de mi liga?</summary>
                    <p>Sí, nuestra plataforma te permite personalizar las reglas y configuraciones de cada liga, incluyendo el formato de competición, los criterios de desempate y más.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Puedo organizar ligas para diferentes deportes?</summary>
                    <p>Sí, nuestra plataforma es versátil y permite la organización de ligas para una variedad de deportes.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Qué sucede si quiero cancelar mi suscripción?</summary>
                    <p>Puedes cancelar tu suscripción en cualquier momento desde tu perfil de usuario. Tendrás acceso a tus ligas hasta el final del período de facturación actual.</p>
                </details>
            </article>

            <article class="container-pregunta">
                <details>
                    <summary>¿Puedo organizar torneos cortos además de ligas largas?</summary>
                    <p>Sí, la plataforma permite la organización tanto de ligas a largo plazo como de torneos cortos y eventos únicos.</p>
                </details>
            </article>
        </section>
    </main>
</x-layout>