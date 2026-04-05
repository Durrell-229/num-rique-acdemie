<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CoursController extends Controller
{
    /**
     * GET /api/cours?niveau=X&q=X
     */
    public function list(Request $request)
    {
        $query  = DB::table('an_cours as c')
            ->select(
                'c.id', 'c.title', 'c.description', 'c.matiere',
                'c.niveau', 'c.url_externe', 'c.by_name', 'c.created_at'
            );

        $niveau = trim($request->query('niveau', ''));
        if ($niveau && in_array($niveau, ['primaire', 'college', 'lycee', 'superieur'])) {
            $query->where('c.niveau', $niveau);
        }

        $q = trim($request->query('q', ''));
        if ($q) {
            $like = '%' . $q . '%';
            $query->where(function ($sub) use ($like) {
                $sub->where('c.title', 'LIKE', $like)
                    ->orWhere('c.matiere', 'LIKE', $like)
                    ->orWhere('c.description', 'LIKE', $like);
            });
        }

        $cours = $query->orderBy('c.created_at', 'desc')->get()->toArray();

        if (!empty($cours)) {
            $ids = array_column($cours, 'id');
            $allFiles = DB::table('an_fichiers')
                ->select('id', 'cours_id', 'type', 'name', 'mime_type', 'size_label', 'data')
                ->whereIn('cours_id', $ids)
                ->orderBy('id')
                ->get()
                ->toArray();

            $filesMap = [];
            foreach ($allFiles as $f) {
                $filesMap[$f->cours_id][] = $f;
            }

            foreach ($cours as &$c) {
                $c = (array) $c;
                $attached      = $filesMap[$c['id']] ?? [];
                $c['files']    = [];
                $c['videos']   = [];
                foreach ($attached as $f) {
                    if ($f->type === 'fichier') {
                        $c['files'][]  = (array) $f;
                    } else {
                        $c['videos'][] = (array) $f;
                    }
                }
            }
            unset($c);
        } else {
            $cours = [];
        }

        return response()->json(['cours' => $cours], 200);
    }

    /**
     * GET /api/cours/{id}
     */
    public function get(Request $request, $id)
    {
        $id = (int) $id;
        if (!$id) {
            return response()->json(['error' => 'ID manquant.'], 400);
        }

        $cours = DB::table('an_cours')->where('id', $id)->first();
        if (!$cours) {
            return response()->json(['error' => 'Cours introuvable.'], 404);
        }

        $cours = (array) $cours;

        $attached = DB::table('an_fichiers')
            ->select('id', 'type', 'name', 'mime_type', 'size_label', 'data')
            ->where('cours_id', $id)
            ->orderBy('id')
            ->get();

        $cours['files']  = [];
        $cours['videos'] = [];
        foreach ($attached as $f) {
            if ($f->type === 'fichier') {
                $cours['files'][]  = (array) $f;
            } else {
                $cours['videos'][] = (array) $f;
            }
        }

        return response()->json(['cours' => $cours], 200);
    }

    /**
     * POST /api/cours
     */
    public function create(Request $request)
    {
        $admin = $request->attributes->get('auth_user');

        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:200',
            'matiere' => 'required|string|max:80',
            'niveau'  => 'required|string|in:primaire,college,lycee,superieur',
        ], [
            'title.required'   => 'Titre, matière et niveau sont obligatoires.',
            'matiere.required' => 'Titre, matière et niveau sont obligatoires.',
            'niveau.required'  => 'Titre, matière et niveau sont obligatoires.',
            'niveau.in'        => 'Niveau invalide.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $url    = trim($request->input('url_externe', ''));
        $files  = $request->input('files', []);
        $videos = $request->input('videos', []);

        DB::beginTransaction();
        try {
            $coursId = DB::table('an_cours')->insertGetId([
                'title'       => trim($request->title),
                'description' => trim($request->input('description', '')),
                'matiere'     => trim($request->matiere),
                'niveau'      => $request->niveau,
                'url_externe' => $url ?: null,
                'by_name'     => $admin->name,
                'by_user_id'  => $admin->id,
                'created_at'  => now(),
            ]);

            foreach ($files as $f) {
                if (empty($f['data']) || empty($f['name'])) continue;
                DB::table('an_fichiers')->insert([
                    'cours_id'   => $coursId,
                    'type'       => 'fichier',
                    'name'       => $f['name'],
                    'mime_type'  => $f['type'] ?? null,
                    'size_label' => $f['size'] ?? null,
                    'data'       => $f['data'],
                    'created_at' => now(),
                ]);
            }

            foreach ($videos as $v) {
                if (empty($v['data']) || empty($v['name'])) continue;
                DB::table('an_fichiers')->insert([
                    'cours_id'   => $coursId,
                    'type'       => 'video',
                    'name'       => $v['name'],
                    'mime_type'  => $v['type'] ?? null,
                    'size_label' => $v['size'] ?? null,
                    'data'       => $v['data'],
                    'created_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur création cours : ' . $e->getMessage()], 500);
        }

        $created = (array) DB::table('an_cours')->where('id', $coursId)->first();
        $attached = DB::table('an_fichiers')->where('cours_id', $coursId)->get();
        $created['files']  = [];
        $created['videos'] = [];
        foreach ($attached as $f) {
            if ($f->type === 'fichier') {
                $created['files'][]  = (array) $f;
            } else {
                $created['videos'][] = (array) $f;
            }
        }

        return response()->json(['cours' => $created, 'success' => true], 201);
    }

    /**
     * DELETE /api/cours/{id}
     */
    public function delete(Request $request, $id)
    {
        $id = (int) $id;
        if (!$id) {
            return response()->json(['error' => 'ID manquant.'], 400);
        }

        $exists = DB::table('an_cours')->where('id', $id)->exists();
        if (!$exists) {
            return response()->json(['error' => 'Cours introuvable.'], 404);
        }

        DB::table('an_cours')->where('id', $id)->delete();

        return response()->json(['success' => true], 200);
    }
}
