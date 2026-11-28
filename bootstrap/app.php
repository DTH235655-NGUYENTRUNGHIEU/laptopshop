<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SeparateAdminSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Chạy đầu tiên cho mọi request để tách hẳn 2 cookie session độc lập
        $middleware->prepend(SeparateAdminSession::class);

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.home');
            }

            return route('frontend.home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();