<?php

use App\Http\Middleware\LangueMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TimeRestriction;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'set.language' => LangueMiddleware::class,
            'time.restrict' => TimeRestriction::class
        ]);

        $middleware->append(TimeRestriction::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
