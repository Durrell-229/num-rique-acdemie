<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementsController extends Controller
{
    /**
     * GET /api/paiements/verifier?cours_id=X
     */
    public function verifier(Request $request)
    {
        $user     = $request->attributes->get('auth_user');
        $cours_id = (int) $request->query('cours_id', 0);

        if (!$cours_id) {
            return response()->json(['error' => 'cours_id manquant.'], 400);
        }

        $row = DB::table('an_paiements')
            ->where('user_id', $user->id)
            ->where('cours_id', $cours_id)
            ->first();

        $paye = ($row && $row->statut === 'valide');

        return response()->json(['paye' => $paye], 200);
    }

    /**
     * POST /api/paiements/confirmer
     */
    public function confirmer(Request $request)
    {
        $user     = $request->attributes->get('auth_user');
        $cours_id = (int) $request->input('cours_id', 0);

        if (!$cours_id) {
            return response()->json(['error' => 'cours_id manquant.'], 400);
        }

        $exists = DB::table('an_cours')->where('id', $cours_id)->exists();
        if (!$exists) {
            return response()->json(['error' => 'Cours introuvable.'], 404);
        }

        $transaction_id = trim($request->input('transaction_id', ''));
        $montant        = (int) $request->input('montant', 0);

        DB::table('an_paiements')->upsert(
            [
                'user_id'        => $user->id,
                'cours_id'       => $cours_id,
                'transaction_id' => $transaction_id,
                'montant'        => $montant,
                'statut'         => 'valide',
                'created_at'     => now(),
            ],
            ['user_id', 'cours_id'],
            ['transaction_id', 'montant', 'statut']
        );

        return response()->json(['success' => true, 'paye' => true], 200);
    }

    /**
     * GET /api/paiements (admin uniquement)
     */
    public function list(Request $request)
    {
        $rows = DB::table('an_paiements as p')
            ->join('an_users as u',  'u.id', '=', 'p.user_id')
            ->join('an_cours as c',  'c.id', '=', 'p.cours_id')
            ->select(
                'p.id', 'p.transaction_id', 'p.montant', 'p.statut', 'p.created_at',
                'u.name as user_name', 'u.email as user_email',
                'c.title as cours_title'
            )
            ->orderBy('p.created_at', 'desc')
            ->get()
            ->map(fn($r) => (array) $r)
            ->toArray();

        return response()->json(['paiements' => $rows], 200);
    }
}
