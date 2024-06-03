<?php

use App\Http\Middleware\EresTu;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EsOrganizador;
use App\Http\Middleware\EsOrganizadorDeLiga;
use App\Http\Middleware\ParticipaPartido;
use App\Http\Middleware\YaComprado;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            "EsOrganizador" => EsOrganizador::class,
            "EsOrganizadorDeLiga" => EsOrganizadorDeLiga::class,
            "ParticipaPartido" => ParticipaPartido::class,
            "YaComprado" => YaComprado::class,
            "EresTu" => EresTu::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
