<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\CustomAuth;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        // $middleware->alias([
        //     'custom_auth' => EnsureUserIsLoggedIn::class,
        // ]);
          $middleware->alias([
            'custom_auth' => CustomAuth::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'permission' =>\App\Http\Middleware\PermissionMiddleware::class,
            'role_or_permission' =>\App\Http\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
