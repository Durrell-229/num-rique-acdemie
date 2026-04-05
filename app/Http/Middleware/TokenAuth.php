<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokenAuth
{
    /**
     * Paramètre $role :
     * - null      → tout utilisateur connecté
     * - 'admin'   → admin uniquement
     * - 'staff'   → admin ou enseignant
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        $token = $request->header('X-Token')
            ?? $request->header('x-token')
            ?? $request->query('token');

        if (!$token)
            return response()->json(['error' => 'Non authentifié.'], 401);

        $user = DB::table('an_sessions as s')
            ->join('an_users as u', 'u.id', '=', 's.user_id')
            ->where('s.token', $token)
            ->where('s.expires_at', '>', now())
            ->select('u.*')
            ->first();

        if (!$user)
            return response()->json(['error' => 'Session expirée. Reconnectez-vous.'], 401);

        // Restriction rôle
        if ($role === 'admin' && $user->role !== 'admin')
            return response()->json(['error' => 'Accès réservé aux administrateurs.'], 403);

        if ($role === 'staff' && !in_array($user->role, ['admin', 'enseignant']))
            return response()->json(['error' => 'Accès réservé au personnel.'], 403);

        $request->attributes->set('auth_user', $user);
        return $next($request);
    }
}
