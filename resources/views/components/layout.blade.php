@props(['deportes', 'user'])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportsLeagueManager</title>
    <link rel="shortcut icon" href="{{asset('img/icono-sinFondo.png')}}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
</head>

<body>

    <x-nav :deportes="$deportes" :user="$user"></x-nav>


    {{$slot}}

    <x-footer></x-footer>
</body>

</html>