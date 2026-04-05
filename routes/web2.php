<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ACADÉMIE NUMÉRIQUE — Web Routes
|--------------------------------------------------------------------------
*/

// ── Page de connexion ────────────────────────────────────────────────────
Route::get('/', fn() => view('auth.login'))->name('auth.login');
Route::get('/login', fn() => view('auth.login'));
Route::post('/logout', function() {
    return redirect('/');
})->name('auth.logout');

// ── Pages protégées (token vérifié côté JS) ──────────────────────────────
// Le token est dans localStorage, la vérification est côté API (JS)
// Les pages HTML sont accessibles mais les données nécessitent un token valide

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/cours',     fn() => view('cours.index'))->name('cours.index');
Route::get('/salles',    fn() => view('salles.index'))->name('salles.index');
Route::get('/profil',    fn() => view('profil.index'))->name('profil.index');

// ── Pages Admin + Enseignant ──────────────────────────────────────────────
Route::get('/admin/cours',   fn() => view('admin.cours'))->name('admin.cours.index');
Route::get('/admin/salles',  fn() => view('admin.salles'))->name('admin.salles.index');

// ── Pages Admin uniquement ────────────────────────────────────────────────
Route::get('/admin/users',      fn() => view('admin.users'))->name('admin.users.index');
Route::get('/admin/paiements',  fn() => view('admin.paiements'))->name('admin.paiements.index');
