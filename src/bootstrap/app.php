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
    ->withMiddleware(function (Middleware $middleware) {
        // web ミドルウェアにセッションとエラー共有とCSRFを追加
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,          // セッション開始
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,   // $errors をビューへ
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // CSRF対策
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
