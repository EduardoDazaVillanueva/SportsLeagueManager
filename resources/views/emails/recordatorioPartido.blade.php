<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recordatorio de Partido</title>
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
    </style>
</head>

<body>
    <div class="email-container">
        <h1 class="email-title">¡Tu próximo partido está en camino!</h1>
        <p class="email-content">
            ¡Prepárate para el próximo encuentro! Visita nuestra plataforma para conocer todos los detalles de tu próximo partido, incluyendo la hora y tus compañeros de equipo.
        </p>

        <a href="{{ route('/') }}" class="email-button">Ver tu partido</a>

        <div class="email-footer">
            <p>¿Tienes preguntas? Echa un vistazo a nuestra <strong><a href="{{ route('faq') }}" style="text-decoration: none; color: #3498db;">sección de preguntas frecuentes</a></strong> o contáctanos a través de nuestro <strong><a href="{{ route('/') }}" style="text-decoration: none; color: #3498db;">formulario de contacto</a></strong>.</p>
            <p>¡Gracias por formar parte de nuestra comunidad! ¡Nos vemos en el campo!</p>
        </div>
    </div>
</body>

</html>