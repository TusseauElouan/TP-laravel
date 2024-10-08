<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\LangueMiddleware;
use App\Http\Middleware\CheckAdminMiddleware;
use App\Http\Middleware\CheckMotifMiddleware;
use App\Http\Middleware\CheckAbsenceMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'set.language' => LangueMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
