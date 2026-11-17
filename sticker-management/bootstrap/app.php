<?php

use App\Http\Middleware\AnyAuthMiddleware;
use App\Http\Middleware\ApplicantMiddleware;
use App\Http\Middleware\NotLoggedIn;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers')
                ->group(__DIR__.'/../routes/api.php');

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(__DIR__.'/../routes/web.php');
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'not-auth' => NotLoggedIn::class,
            'applicant' => ApplicantMiddleware::class,
            'anyauth' => AnyAuthMiddleware::class,
            'super-admin' => SuperAdminMiddleware::class,
            'prevent_back_history' => PreventBackHistory::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);

        // SSLCommerz payment gateway sends server-to-server POST callbacks
        // without a CSRF token, so exclude them from CSRF verification.
        $middleware->validateCsrfTokens(except: [
            'make_payment',
            'subscriber/sslcommerz/success',
            'subscriber/sslcommerz/cancel',
            'subscriber/sslcommerz/fail',
            'subscriber/sslcommerz/ipn',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
