@props(['deportes'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportsLeagueManager</title>
    <link rel="shortcut icon" href="{{asset('img/icono-sinFondo.png')}}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
<x-nav :deportes="$deportes"></x-nav>
    

    {{$slot}}
</body>
</html>