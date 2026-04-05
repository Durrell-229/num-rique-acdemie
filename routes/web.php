<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ACADÉMIE NUMÉRIQUE — Routes Web
|--------------------------------------------------------------------------
*/

// ── Accès Public ────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('welcome');
Route::get('/home', fn() => view('home'))->name('home');

// ── Authentification (Invités seulement) ────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/register', fn() => view('auth.register'))->name('register');
});

Route::post('/logout', function() {
    // Logique de déconnexion (ex: Auth::logout() si tu utilises les sessions)
    return redirect('/');
})->name('auth.logout');

// ── Espace Apprenant (Authentifié) ──────────────────────────────────────
// Note : Même si tu gères le token en JS, protéger la route ici 
// évite le "flash" de contenu privé avant la redirection JS.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/cours',     fn() => view('cours.index'))->name('cours.index');
    Route::get('/salles',    fn() => view('salles.index'))->name('salles.index');
    Route::get('/profil',    fn() => view('profil.index'))->name('profil.index');

    // ── Espace Administration & Enseignants ─────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        // Accessibles aux admins et profs
        Route::get('/cours',  fn() => view('admin.cours'))->name('cours.index');
        Route::get('/salles', fn() => view('admin.salles'))->name('salles.index');

        // Uniquement Admins (ex: via un middleware personnalisé 'role:admin')
        Route::get('/users',     fn() => view('admin.users'))->name('users.index');
        Route::get('/paiements', fn() => view('admin.paiements'))->name('paiements.index');
    });
});

// ── Route Catch-all pour la SPA ─────────────────────────────────────────
// Si l'utilisateur rafraîchit une page profonde, Laravel doit renvoyer la vue principale
Route::fallback(function () {
    return view('dashboard'); // Ou ta vue principale SPA
});