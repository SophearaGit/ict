<?php

use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\CheckRoleMiddleWare;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureHasReportGrant;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'check_role' => CheckRoleMiddleWare::class,
            'report.grant' => EnsureHasReportGrant::class,
        ]);

        // ABA PayWay POSTs its transaction callback directly to this URL
        // (server-to-server) — it has no session/cookie, so it can never
        // send our CSRF token. Without this exemption, routes/web.php's
        // comment about excluding it was never actually wired up anywhere
        // (there's no App\Http\Middleware\VerifyCsrfToken in this Laravel
        // 11+ app to add an $except entry to), so every real callback from
        // PayWay was silently rejected with a 419 before ever reaching
        // PayWayPaymentController::callback().
        $middleware->validateCsrfTokens(except: [
            'payment/payway/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
