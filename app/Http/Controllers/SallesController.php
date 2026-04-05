<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SallesController extends Controller
{
    /**
     * GET /api/salles
     */
    public function list(Request $request)
    {
        $salles = DB::table('an_salles')
            ->select('id', 'name', 'matiere', 'description', 'room', 'icon', 'by_name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($s) => (array) $s)
            ->toArray();

        return response()->json(['salles' => $salles], 200);
    }

    /**
     * POST /api/salles
     */
    public function create(Request $request)
    {
        $admin = $request->attributes->get('auth_user');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'room' => 'required|string',
        ], [
            'name.required' => 'Nom et identifiant de salle (room) sont obligatoires.',
            'room.required' => 'Nom et identifiant de salle (room) sont obligatoires.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Sanitize room identifier (Jitsi compatible)
        $room = strtolower(preg_replace('/[^a-z0-9\-]/i', '', str_replace(' ', '-', trim($request->room))));

        if (strlen($room) < 3) {
            return response()->json(['error' => "L'identifiant Jitsi doit contenir au moins 3 caractères."], 400);
        }

        $exists = DB::table('an_salles')->where('room', $room)->exists();
        if ($exists) {
            return response()->json(['error' => 'Cet identifiant de salle existe déjà.'], 409);
        }

        $id = DB::table('an_salles')->insertGetId([
            'name'        => trim($request->name),
            'matiere'     => trim($request->input('matiere', '')),
            'description' => trim($request->input('description', '')),
            'room'        => $room,
            'icon'        => trim($request->input('icon', '📚')),
            'by_name'     => $admin->name,
            'by_user_id'  => $admin->id,
            'created_at'  => now(),
        ]);

        $salle = (array) DB::table('an_salles')->where('id', $id)->first();

        return response()->json(['salle' => $salle, 'success' => true], 201);
    }

    /**
     * DELETE /api/salles/{id}
     */
    public function delete(Request $request, $id)
    {
        $id = (int) $id;
        if (!$id) {
            return response()->json(['error' => 'ID manquant.'], 400);
        }

        $exists = DB::table('an_salles')->where('id', $id)->exists();
        if (!$exists) {
            return response()->json(['error' => 'Salle introuvable.'], 404);
        }

        DB::table('an_salles')->where('id', $id)->delete();

        return response()->json(['success' => true], 200);
    }
}
