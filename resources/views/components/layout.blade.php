@props(['deportes'])
@props(['liga'])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportsLeagueManager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    
    <x-nav :deportes="$deportes"></x-nav>

    {{$slot}}
</body>
</html>