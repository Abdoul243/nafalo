<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\ModuleFormation;
use App\Models\Lecon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    /** Garde-fou : le produit appartient à la boutique active. */
    private function autoriserProduit(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    private function autoriserModule(ModuleFormation $module): void
    {
        abort_if($module->produit->boutique_id !== session('boutique_id'), 403);
    }

    private function autoriserLecon(Lecon $lecon): void
    {
        abort_if($lecon->module->produit->boutique_id !== session('boutique_id'), 403);
    }

    /** Page de construction du programme. */
    public function programme(Produit $produit)
    {
        $this->autoriserProduit($produit);

        // S'assurer que le produit est bien marqué comme formation
        if (!$produit->estFormation()) {
            $produit->update(['format' => 'formation']);
        }

        $produit->load(['modules.lecons']);

        return view('admin.formations.programme', compact('produit'));
    }

    /* ── Modules ─────────────────────────────────────────────────── */

    public function storeModule(Request $request, Produit $produit)
    {
        $this->autoriserProduit($produit);

        $data = $request->validate([
            'titre' => 'required|string|max:255',
        ]);

        $produit->modules()->create([
            'titre' => $data['titre'],
            'ordre' => ($produit->modules()->max('ordre') ?? 0) + 1,
        ]);

        return back()->with('success', 'Module ajouté.');
    }

    public function updateModule(Request $request, ModuleFormation $module)
    {
        $this->autoriserModule($module);

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $module->update($data);

        return back()->with('success', 'Module mis à jour.');
    }

    public function destroyModule(ModuleFormation $module)
    {
        $this->autoriserModule($module);

        // Supprimer les fichiers privés des leçons du module
        foreach ($module->lecons as $lecon) {
            $this->supprimerFichiersLecon($lecon);
        }

        $module->delete();

        return back()->with('success', 'Module supprimé.');
    }

    /* ── Leçons ──────────────────────────────────────────────────── */

    public function storeLecon(Request $request, ModuleFormation $module)
    {
        $this->autoriserModule($module);

        $data = $this->validerLecon($request);
        $data['module_id'] = $module->id;
        $data['ordre']     = ($module->lecons()->max('ordre') ?? 0) + 1;

        $this->gererFichiers($request, $data);

        Lecon::create($data);

        return back()->with('success', 'Leçon ajoutée.');
    }

    public function updateLecon(Request $request, Lecon $lecon)
    {
        $this->autoriserLecon($lecon);

        $data = $this->validerLecon($request);
        $this->gererFichiers($request, $data, $lecon);

        $lecon->update($data);

        return back()->with('success', 'Leçon mise à jour.');
    }

    public function destroyLecon(Lecon $lecon)
    {
        $this->autoriserLecon($lecon);

        $this->supprimerFichiersLecon($lecon);
        $lecon->delete();

        return back()->with('success', 'Leçon supprimée.');
    }

    /* ── Helpers ─────────────────────────────────────────────────── */

    private function validerLecon(Request $request): array
    {
        return $request->validate([
            'titre'      => 'required|string|max:255',
            'contenu'    => 'nullable|string',
            'video_url'  => 'nullable|url|max:500',
            'duree'      => 'nullable|integer|min:0|max:100000',
            'est_apercu' => 'nullable|boolean',
            'ordre'      => 'nullable|integer|min:0',
            'video_fichier'     => 'nullable|file|mimes:mp4,webm,mov,m4v|max:512000',   // 500 Mo
            'ressource_fichier' => 'nullable|file|max:102400',                          // 100 Mo
        ]);
    }

    /** Gère l'upload (disque PRIVÉ) des fichiers vidéo et ressource. */
    private function gererFichiers(Request $request, array &$data, ?Lecon $lecon = null): void
    {
        $data['est_apercu'] = $request->boolean('est_apercu');

        if ($request->hasFile('video_fichier')) {
            if ($lecon && $lecon->video_fichier) {
                Storage::disk('local')->delete($lecon->video_fichier);
            }
            $data['video_fichier'] = $request->file('video_fichier')->store('formations/videos', 'local');
        }

        if ($request->hasFile('ressource_fichier')) {
            if ($lecon && $lecon->ressource_fichier) {
                Storage::disk('local')->delete($lecon->ressource_fichier);
            }
            $data['ressource_fichier'] = $request->file('ressource_fichier')->store('formations/ressources', 'local');
        }

        // Nettoyer les champs upload du tableau si aucun fichier (évite d'écraser par null à l'update)
        if (!$request->hasFile('video_fichier'))     unset($data['video_fichier']);
        if (!$request->hasFile('ressource_fichier')) unset($data['ressource_fichier']);
    }

    private function supprimerFichiersLecon(Lecon $lecon): void
    {
        if ($lecon->video_fichier)     Storage::disk('local')->delete($lecon->video_fichier);
        if ($lecon->ressource_fichier) Storage::disk('local')->delete($lecon->ressource_fichier);
    }
}
