<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a la Liga</title>

    <style>
        .email-container {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .email-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .email-content {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .email-button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }

        .email-footer {
            font-size: 14px;
            margin-top: 20px;
        }

        .lista {
            list-style: none;
            font-size: 16px;

            padding: 0;
        }

        .container_img {
            width: 100%;
            height: fit-content;
        }

        .imagen {
            aspect-ratio: 1 / 1;
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="container_img">
            <img class="imagen" src="https://i.imgur.com/FlqZucQ.png" alt="Logo de la web">
        </div>
        <h1 class="email-title">¡Te invitamos a unirte a nuestra liga!</h1>

        <p class="email-content">Espero que estés bien. Quería aprovechar esta oportunidad para invitarte a unirte a nuestra liga.</p>

        <p class="email-content">A continuación, te proporciono los detalles de la liga:</p>

        <ul class="lista">
            <li><strong>Nombre:</strong> {{ $liga->nombre }}</li>
            <li><strong>Ubicación:</strong> {{ $liga->sede }} en {{ $liga->localdidad }} </li>
            <li><strong>Horario:</strong> Desde las {{ substr($liga->primera_hora, 0, 5) }} hasta las {{ substr($liga->ultima_hora, 0, 5) }}</li>
            <li><strong>Días de juego:</strong>
                @for ($i = 0; $i < count($liga->dia_jornada); $i++)
                    @if ($i == (count($liga->dia_jornada) -2) )
                    {{ $liga->dia_jornada[$i]}} y
                    @elseif ($i == (count($liga->dia_jornada) -1))
                    {{ $liga->dia_jornada[$i]}}.
                    @else
                    {{ $liga->dia_jornada[$i]}},
                    @endif
                    @endfor
            </li>
        </ul>

        <p class="email-content">Nuestra liga ofrece un ambiente amistoso y competitivo donde todos los niveles de habilidad son bienvenidos. Ya sea que seas un jugador experimentado o estés comenzando, encontrarás un lugar en nuestra liga.</p>

        <a href="{{ route('liga.show', ['liga' => $liga->id]) }}" class="email-button">Unirse</a>

        <p class="email-content">¡Esperamos verte en la pista!</p>

        <div class="email-footer">
            <p>¿Tienes preguntas? Echa un vistazo a nuestra <strong><a href="{{ route('faq') }}" style="text-decoration: none; color: #3498db;">sección de preguntas frecuentes</a></strong> o contáctanos a través de nuestro <strong><a href="{{ route('welcome') }}" style="text-decoration: none; color: #3498db;">formulario de contacto</a></strong>.</p>
            <p>¡Gracias por formar parte de nuestra comunidad! ¡Nos vemos en el campo!</p>
        </div>
    </div>
</body>

</html>