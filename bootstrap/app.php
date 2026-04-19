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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo('/customer/products');
        $middleware->alias([
            'IsActive' => \App\Http\Middleware\IsActive::class,
            'IsAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\GetCart::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
