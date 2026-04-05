<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\SallesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\BootController;
use App\Http\Controllers\PaiementsController;

/*
|--------------------------------------------------------------------------
| ACADÉMIE NUMÉRIQUE — API Routes
|--------------------------------------------------------------------------
| Rôles :
|  - token.auth         → tout utilisateur connecté
|  - token.auth:staff   → admin ou enseignant
|  - token.auth:admin   → admin uniquement
*/

// ── Auth publique ─────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login',          [AuthController::class, 'login']);
    Route::post('/register',       [AuthController::class, 'register']);
    Route::post('/register-admin', [AuthController::class, 'registerAdmin']);

    Route::middleware('token.auth')->group(function () {
        Route::get('/me',       [AuthController::class, 'me']);
        Route::post('/ping',    [AuthController::class, 'ping']);
        Route::post('/offline', [AuthController::class, 'offline']);
        Route::post('/logout',  [AuthController::class, 'logout']);
    });
});

// ── Boot (1 seul appel au chargement) ────────────────────────────────────
Route::get('/boot', [BootController::class, 'load'])->middleware('token.auth');

// ── Cours ─────────────────────────────────────────────────────────────────
Route::prefix('cours')->group(function () {
    // Lecture → tout utilisateur connecté
    Route::get('/',      [CoursController::class, 'list'])->middleware('token.auth');
    Route::get('/{id}',  [CoursController::class, 'get'])->middleware('token.auth');
    // Écriture → admin ou enseignant
    Route::post('/',          [CoursController::class, 'create'])->middleware('token.auth:staff');
    Route::delete('/{id}',    [CoursController::class, 'delete'])->middleware('token.auth:staff');
});

// ── Salles ────────────────────────────────────────────────────────────────
Route::prefix('salles')->group(function () {
    Route::get('/',        [SallesController::class, 'list'])->middleware('token.auth');
    Route::post('/',       [SallesController::class, 'create'])->middleware('token.auth:staff');
    Route::delete('/{id}', [SallesController::class, 'delete'])->middleware('token.auth:staff');
});

// ── Utilisateurs ──────────────────────────────────────────────────────────
Route::prefix('users')->group(function () {
    // Liste complète → admin uniquement (enseignants ne voient pas les élèves)
    Route::get('/',        [UsersController::class, 'list'])->middleware('token.auth:admin');
    Route::get('/stats',   [UsersController::class, 'stats'])->middleware('token.auth');
    // Profil personnel → tout utilisateur
    Route::post('/profile',[UsersController::class, 'updateProfile'])->middleware('token.auth');
    Route::post('/photo',  [UsersController::class, 'updatePhoto'])->middleware('token.auth');
    // Suppression → admin uniquement
    Route::delete('/{id}', [UsersController::class, 'delete'])->middleware('token.auth:admin');
});

// ── Paiements ─────────────────────────────────────────────────────────────
Route::prefix('paiements')->group(function () {
    Route::get('/verifier',   [PaiementsController::class, 'verifier'])->middleware('token.auth');
    Route::post('/confirmer', [PaiementsController::class, 'confirmer'])->middleware('token.auth');
    // Liste → admin uniquement
    Route::get('/',           [PaiementsController::class, 'list'])->middleware('token.auth:admin');
});
