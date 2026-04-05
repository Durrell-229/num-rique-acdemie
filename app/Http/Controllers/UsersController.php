<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function list(Request $request)
    {
        // Admin seulement — middleware gère déjà ça
        DB::statement("UPDATE an_users SET online=0 WHERE online=1 AND last_seen < DATE_SUB(NOW(), INTERVAL 30 SECOND)");
        $users = DB::table('an_users')
            ->select('id','name','email','role','pays','localite','classe','photo','online','last_seen','created_at')
            ->orderBy('created_at','desc')->get()->map(fn($u)=>(array)$u)->toArray();
        return response()->json(['users'=>$users]);
    }

    public function stats(Request $request)
    {
        $r = DB::selectOne("SELECT COUNT(*) AS total, SUM(role='eleve') AS eleves, SUM(online=1) AS en_ligne, COUNT(DISTINCT pays) AS pays FROM an_users");
        return response()->json([
            'total_users' => (int)$r->total,
            'eleves'      => (int)$r->eleves,
            'en_ligne'    => (int)$r->en_ligne,
            'pays'        => (int)$r->pays,
            'cours'       => DB::table('an_cours')->count(),
            'salles'      => DB::table('an_salles')->count(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $name = trim($request->input('name',''));
        $pays = $request->input('pays','');
        $localite = $request->input('localite','');
        $pass = $request->input('password','');
        if (!$name) return response()->json(['error'=>'Le nom est obligatoire.'],400);
        if ($pass && strlen($pass)<6) return response()->json(['error'=>'Mot de passe : minimum 6 caractères.'],400);

        $data = ['name'=>$name,'pays'=>$pays,'localite'=>$localite];
        if ($pass) $data['password'] = Hash::make($pass);
        DB::table('an_users')->where('id',$user->id)->update($data);

        $u = (array)DB::table('an_users')->where('id',$user->id)->first(); unset($u['password']);
        return response()->json(['user'=>$u,'success'=>true]);
    }

    public function updatePhoto(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $photo = $request->input('photo','');
        if (!$photo) return response()->json(['error'=>'Aucune photo.'],400);
        DB::table('an_users')->where('id',$user->id)->update(['photo'=>$photo]);
        return response()->json(['success'=>true,'photo'=>$photo]);
    }

    public function delete(Request $request, $id)
    {
        $admin = $request->attributes->get('auth_user');
        $target = (int)$id;
        if (!$target) return response()->json(['error'=>'ID manquant.'],400);
        if ($target===(int)$admin->id) return response()->json(['error'=>'Impossible de supprimer votre propre compte.'],400);
        if (!DB::table('an_users')->where('id',$target)->exists()) return response()->json(['error'=>'Utilisateur introuvable.'],404);
        DB::table('an_users')->where('id',$target)->delete();
        return response()->json(['success'=>true]);
    }
}
