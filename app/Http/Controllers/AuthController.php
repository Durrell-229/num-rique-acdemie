<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
        if ($v->fails()) return response()->json(['error' => $v->errors()->first()], 400);

        $user = DB::table('an_users')->where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password))
            return response()->json(['error' => 'Email ou mot de passe incorrect.'], 401);

        $token = bin2hex(random_bytes(32));
        DB::table('an_sessions')->insert(['user_id'=>$user->id,'token'=>$token,'expires_at'=>now()->addDays(7),'created_at'=>now()]);
        DB::table('an_users')->where('id',$user->id)->update(['online'=>1,'last_seen'=>now()]);

        $u = (array)$user; unset($u['password']);
        return response()->json(['token'=>$token,'user'=>$u]);
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:an_users,email',
            'password' => 'required|string|min:6',
            'pays'     => 'required|string',
            'localite' => 'required|string',
            'classe'   => 'required|string',
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.min' => 'Mot de passe : minimum 6 caractères.',
        ]);
        if ($v->fails()) return response()->json(['error' => $v->errors()->first()], 400);

        $id = DB::table('an_users')->insertGetId([
            'name'=>trim($request->name),'email'=>trim($request->email),
            'password'=>Hash::make($request->password),'role'=>'eleve',
            'pays'=>$request->pays,'localite'=>$request->localite,
            'classe'=>$request->classe,'photo'=>$request->photo??null,
            'online'=>1,'last_seen'=>now(),'created_at'=>now(),
        ]);
        $token = bin2hex(random_bytes(32));
        DB::table('an_sessions')->insert(['user_id'=>$id,'token'=>$token,'expires_at'=>now()->addDays(7),'created_at'=>now()]);

        $u = (array)DB::table('an_users')->where('id',$id)->first(); unset($u['password']);
        return response()->json(['token'=>$token,'user'=>$u], 201);
    }

    public function registerAdmin(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:an_users,email',
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:admin,enseignant',
            'admin_code' => 'required|string',
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.min' => 'Mot de passe : minimum 6 caractères.',
            'role.in'      => 'Rôle invalide.',
        ]);
        if ($v->fails()) return response()->json(['error' => $v->errors()->first()], 400);

        $role = $request->role;
        $code = $request->admin_code;

        // Codes séparés selon le rôle
        $adminCode      = config('academie.admin_code',      'ADMIN2025');
        $enseignantCode = config('academie.enseignant_code', 'ENSEIGNANT2025');

        if ($role === 'admin' && $code !== $adminCode)
            return response()->json(['error' => "Code administrateur incorrect."], 403);

        if ($role === 'enseignant' && $code !== $enseignantCode)
            return response()->json(['error' => "Code enseignant incorrect."], 403);

        $classe = ($role === 'admin') ? 'Administrateur' : 'Enseignant';

        $id = DB::table('an_users')->insertGetId([
            'name'=>trim($request->name),'email'=>trim($request->email),
            'password'=>Hash::make($request->password),'role'=>$role,
            'pays'=>'--','localite'=>'--','classe'=>$classe,
            'online'=>1,'last_seen'=>now(),'created_at'=>now(),
        ]);
        $token = bin2hex(random_bytes(32));
        DB::table('an_sessions')->insert(['user_id'=>$id,'token'=>$token,'expires_at'=>now()->addDays(7),'created_at'=>now()]);

        $u = (array)DB::table('an_users')->where('id',$id)->first(); unset($u['password']);
        return response()->json(['token'=>$token,'user'=>$u], 201);
    }

    public function logout(Request $request)
    {
        $token = $request->header('X-Token') ?? $request->query('token');
        if ($token) {
            $s = DB::table('an_sessions')->where('token',$token)->first();
            if ($s) DB::table('an_users')->where('id',$s->user_id)->update(['online'=>0,'last_seen'=>now()]);
            DB::table('an_sessions')->where('token',$token)->delete();
        }
        return response()->json(['success'=>true]);
    }

    public function me(Request $request)
    {
        $u = (array)$request->attributes->get('auth_user'); unset($u['password']);
        return response()->json(['user'=>$u]);
    }

    public function ping(Request $request)
    {
        $u = $request->attributes->get('auth_user');
        DB::table('an_users')->where('id',$u->id)->update(['online'=>1,'last_seen'=>now()]);
        return response()->json(['online'=>true]);
    }

    public function offline(Request $request)
    {
        $u = $request->attributes->get('auth_user');
        DB::table('an_users')->where('id',$u->id)->update(['online'=>0,'last_seen'=>now()]);
        return response()->json(['online'=>false]);
    }
}
