<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Añade esto para redirigir a los usuarios autenticados
        $middleware->redirectTo(
            guests: '/login',
            users: '/buscar'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();