<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/user.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'check.approved' => \App\Http\Middleware\CheckApproved::class,
            'check.admin' => \App\Http\Middleware\CheckAdmin::class,
            'check.role' => \App\Http\Middleware\CheckRole::class,
            'check_admin_role' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
