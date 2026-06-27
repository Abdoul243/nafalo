<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Http\Requests\BoutiqueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BoutiqueController extends Controller
{
    public function index()
    {
        $boutiques = Boutique::paginate(10);
        return view('admin.boutiques.index', compact('boutiques'));
    }
    
    public function create()
    {
        return view('admin.boutiques.create');
    }

    /**
     * Vérification AJAX de la disponibilité du domaine (étape 5-6 du diagramme)
     */
    public function checkDomain(Request $request)
    {
        $domaine = trim($request->get('domaine', ''));

        if (empty($domaine)) {
            return response()->json(['available' => true, 'message' => '']);
        }

        // Slug-ify le domaine
        $domaine = Str::slug($domaine);

        $exists = Boutique::where('domaine_personnalise', $domaine)->exists();

        return response()->json([
            'available' => !$exists,
            'domaine'   => $domaine,
            'message'   => $exists
                ? 'Ce domaine est déjà utilisé par une autre boutique.'
                : 'Disponible !',
        ]);
    }

    public function store(BoutiqueRequest $request)
    {
        $data = $request->validated();

        // Slug-ify le domaine si renseigné
        if (!empty($data['domaine_personnalise'])) {
            $data['domaine_personnalise'] = Str::slug($data['domaine_personnalise']);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $data['logo'] = file_get_contents($logo->getRealPath());
            $data['logo_mime'] = $logo->getMimeType();
            $data['logo_taille'] = $logo->getSize();
        }

        // Étape 9 : Enregistrer la boutique en base
        $boutique = Boutique::create($data);

        // Étape 11 : INSERT configurations_boutique (paramètres par défaut)
        $boutique->configuration()->create([
            'devise'               => 'XOF',
            'relance_delai_jours'  => 3,
        ]);

        // Étape 13-14 : Retourner + message succès + redirection dashboard
        return redirect()->route('admin.dashboard')
            ->with('success', '🎉 Ta boutique "' . $boutique->nom . '" est créée et accessible !');
    }
    
    /**
     * Garde-fou : la boutique doit appartenir à l'utilisateur connecté.
     */
    private function verifierProprietaire(Boutique $boutique): void
    {
        abort_if($boutique->utilisateur_id !== auth()->id(), 403);
    }

    public function edit(Boutique $boutique)
    {
        $this->verifierProprietaire($boutique);

        return view('admin.boutiques.edit', compact('boutique'));
    }

    public function update(BoutiqueRequest $request, Boutique $boutique)
    {
        $this->verifierProprietaire($boutique);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $data['logo'] = file_get_contents($logo->getRealPath());
            $data['logo_mime'] = $logo->getMimeType();
            $data['logo_taille'] = $logo->getSize();
        }

        $boutique->update($data);

        return redirect()->route('admin.boutiques.index')
            ->with('success', 'Boutique mise à jour avec succès.');
    }

    public function destroy(Boutique $boutique)
    {
        $this->verifierProprietaire($boutique);

        $boutique->delete();

        return redirect()->route('admin.boutiques.index')
            ->with('success', 'Boutique supprimée avec succès.');
    }

    public function toggleActivation(Boutique $boutique)
    {
        $this->verifierProprietaire($boutique);

        $boutique->update(['est_active' => !$boutique->est_active]);

        return response()->json([
            'success' => true,
            'est_active' => $boutique->est_active
        ]);
    }
}
