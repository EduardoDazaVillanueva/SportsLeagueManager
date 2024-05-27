<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            padding: 20px;
        }

        .email-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            text-align: center;
        }

        .email-title {
            font-size: 24px;
            font-weight: bold;
            color: #040738;
        }

        .email-content {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .email-button {
            background-color: #040738;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .email-button:hover {
            background-color: #0056b3;
        }

        .email-footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
        }

        .email-footer a {
            color: #040738;
            text-decoration: none;
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
        <h1 class="email-title">¡Gracias por registrarte!</h1>
        <p class="email-content">
            Bienvenido a nuestra plataforma. Por favor, haz clic en el siguiente enlace para verificar tu correo electrónico y activar tu cuenta.
        </p>

        <a href="{{ $verificationLink }}" class="email-button" style="text-decoration: none; color: #fff;">Verificar Correo Electrónico</a>
        <br><br>

        <p class="email-content">
            Una vez verificado tu correo, podrás acceder a todas las funciones de nuestra plataforma. Si no esperabas este correo, puedes ignorarlo.
        </p>

        <div class="email-footer">
            <p>Si necesitas ayuda, visita nuestra <strong><a href="{{ route('faq') }}">sección de preguntas frecuentes</a></strong> o contáctanos a través de nuestro <strong>formulario de contacto</a></strong>.</p>
            <p>Gracias por unirte a nosotros. ¡Te esperamos!</p>
        </div>
    </div>
</body>

</html>