<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ── Enregistrer le middleware token personnalisé ──────────────────
        $middleware->alias([
            'token.auth' => \App\Http\Middleware\TokenAuth::class,
        ]);

        // ── Exclure les routes /api/* du CSRF ─────────────────────────────
        // Sans ça, toutes les requêtes POST vers /api/* retournent
        // une page HTML 419 au lieu de JSON → "Unexpected token '<'"
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Retourner du JSON pour toutes les erreurs sur les routes API
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                return response()->json([
                    'error' => $e->getMessage() ?: 'Erreur serveur.',
                ], $status);
            }
        });
    })->create();
