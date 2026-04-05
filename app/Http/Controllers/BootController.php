<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BootController extends Controller
{
    /**
     * GET /api/boot
     * Charge toutes les données en un seul appel (optimisation)
     */
    public function load(Request $request)
    {
        $user = $request->attributes->get('auth_user');

        // Marquer inactifs
        try {
            DB::statement("UPDATE an_users SET online = 0 WHERE online = 1 AND last_seen < DATE_SUB(NOW(), INTERVAL 30 SECOND)");
        } catch (\Exception $e) {}

        // Stats
        try {
            $stats = DB::selectOne("SELECT COUNT(*) AS total, SUM(role='eleve') AS eleves, SUM(online=1) AS en_ligne, COUNT(DISTINCT pays) AS pays FROM an_users");
        } catch (\Exception $e) {
            $stats = (object)['total'=>0,'eleves'=>0,'en_ligne'=>0,'pays'=>0];
        }

        $nCours  = DB::table('an_cours')->count();
        $nSalles = DB::table('an_salles')->count();

        // Cours sans base64
        $cours = [];
        try {
            $cours = DB::table('an_cours as c')
                ->select('c.id','c.title','c.description','c.matiere','c.niveau','c.url_externe','c.prix','c.by_name','c.created_at')
                ->orderBy('c.created_at','desc')
                ->get()->toArray();

            if (!empty($cours)) {
                $ids = array_column($cours, 'id');
                $allFiles = DB::table('an_fichiers')
                    ->select('id','cours_id','type','name','mime_type','size_label')
                    ->whereIn('cours_id', $ids)
                    ->orderBy('id')
                    ->get()->toArray();

                $filesMap = [];
                foreach ($allFiles as $f) {
                    $filesMap[$f->cours_id][] = $f;
                }

                foreach ($cours as &$c) {
                    $c = (array) $c;
                    $attached    = $filesMap[$c['id']] ?? [];
                    $c['files']  = [];
                    $c['videos'] = [];
                    foreach ($attached as $f) {
                        if ($f->type === 'fichier') $c['files'][]  = (array)$f;
                        else                        $c['videos'][] = (array)$f;
                    }
                }
                unset($c);
            }
        } catch (\Exception $e) {}

        // Salles
        $salles = [];
        try {
            $salles = DB::table('an_salles')
                ->select('id','name','matiere','description','room','icon','by_name','created_at')
                ->orderBy('created_at','desc')
                ->get()->map(fn($s) => (array)$s)->toArray();
        } catch (\Exception $e) {}

        // Users
        $users = [];
        try {
            $users = DB::table('an_users')
                ->select('id','name','email','role','pays','classe','photo','online','last_seen','created_at')
                ->orderBy('created_at','desc')
                ->get()->map(fn($u) => (array)$u)->toArray();
        } catch (\Exception $e) {}

        $userData = (array) $user;
        unset($userData['password']);

        return response()->json([
            'user'   => $userData,
            'stats'  => [
                'total_users' => (int)$stats->total,
                'eleves'      => (int)$stats->eleves,
                'en_ligne'    => (int)$stats->en_ligne,
                'pays'        => (int)$stats->pays,
                'cours'       => $nCours,
                'salles'      => $nSalles,
            ],
            'cours'  => $cours,
            'salles' => $salles,
            'users'  => $users,
        ], 200);
    }
}
