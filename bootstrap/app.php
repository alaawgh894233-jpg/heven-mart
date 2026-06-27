<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // هذا alias (فقط class)
        $middleware->alias([
            'set.lang' => \App\Http\Middleware\SetAppLanguage::class,
            'performance' => \App\Http\Middleware\PerformanceMiddleware::class,
            'error.handler' => \App\Http\Middleware\ErrorHandlerMiddleware::class,
            'logging.aspect' =>
                \App\Http\Middleware\LoggingAspect::class,
        ]);

        // هذا group (array مسموح)
        $middleware->group('api', [
            \App\Http\Middleware\SetAppLanguage::class,
          \App\Http\Middleware\PerformanceMiddleware::class,
            \App\Http\Middleware\ErrorHandlerMiddleware::class,
            \App\Http\Middleware\LoggingAspect::class,

//            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,


        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
