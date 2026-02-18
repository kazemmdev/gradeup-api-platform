<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using   : function () {
            Route::middleware('api')->prefix('api/v1')->group(__DIR__ . '/../routes/api/v1/public.php');
            Route::middleware('api')->prefix('api/v1/auth')->as('auth.')->group(__DIR__ . '/../routes/api/v1/auth.php');
            Route::middleware(['api', 'auth:api'])->prefix('api/v1')->as('protected.')->group(__DIR__ . '/../routes/api/v1/protected.php');
        },
        web     : __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api([
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
