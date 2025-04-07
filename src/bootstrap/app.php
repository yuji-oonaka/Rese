<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RepresentativeMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // グローバルミドルウェア
        $middleware->use([
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        ]);

        // Webミドルウェアグループ
        $middleware->web([
            \Illuminate\Cookie\Middleware\EncryptCookies::class, // 修正箇所
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // 修正箇所
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // APIミドルウェアグループ
        $middleware->api([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // 管理者用ミドルウェアグループ
        $middleware->appendToGroup('admin', [
            AdminMiddleware::class,
        ]);

        // 店舗代表者用ミドルウェアグループ
        $middleware->appendToGroup('representative', [
            RepresentativeMiddleware::class,
        ]);

        // CSRFトークン検証除外設定（新しい方法）
        $middleware->validateCsrfTokens(
            except: [
                '/stripe/*',
                '/webhook'
            ]
        );

        // ミドルウェアエイリアス
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
