<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware for web
        $middleware->web(append: [
            \App\Http\Middleware\TrackUserSession::class,
        ]);

        // Middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.checkout.access' => \App\Http\Middleware\CheckCheckoutAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if (! $request->is('admin/moment/media') || ! $request->isMethod('POST')) {
                return null;
            }
            $limit = preg_replace('/([KMG])B?$/i', ' $1B', strtoupper(trim(ini_get('post_max_size'))));
            $message = 'Ukuran total upload melebihi batas request server ('.$limit.'). Pilih foto yang lebih kecil.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message, 'errors' => ['image' => [$message]]], 413);
            }
            return back()->withErrors(['image' => $message]);
        });

        // Handle 419 CSRF token mismatch errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return back()->with([
                    'error' => 'Your session has expired. Please try again.',
                    'csrf_expired' => true
                ])->withInput($request->except('password', '_token'));
            }
        });
    })->create();
