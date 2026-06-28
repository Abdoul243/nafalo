<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\CleLicence;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LicenceController extends Controller
{
    private function autoriser(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    /** Page de gestion des clés de licence d'un produit. */
    public function gestion(Produit $produit)
    {
        $this->autoriser($produit);

        if (!$produit->estLicence()) {
            $produit->update(['format' => 'licence']);
        }

        $disponibles = $produit->clesLicence()->where('statut', 'disponible')->count();
        $attribuees  = $produit->clesLicence()->where('statut', 'attribuee')
            ->with('client')->latest('attribuee_at')->limit(50)->get();

        return view('admin.licences.gestion', compact('produit', 'disponibles', 'attribuees'));
    }

    /** Ajoute des clés collées (une par ligne). */
    public function ajouter(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate(['cles' => 'required|string']);

        $lignes = preg_split('/\r\n|\r|\n/', $data['cles']);
        $ajoutees = 0;

        foreach ($lignes as $ligne) {
            $cle = trim($ligne);
            if ($cle === '') continue;

            $existe = $produit->clesLicence()->where('cle', $cle)->exists();
            if ($existe) continue;

            $produit->clesLicence()->create(['cle' => $cle, 'statut' => 'disponible']);
            $ajoutees++;
        }

        return back()->with('success', "$ajoutees clé(s) ajoutée(s).");
    }

    /** Génère automatiquement N clés (format XXXX-XXXX-XXXX-XXXX). */
    public function generer(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate([
            'quantite' => 'required|integer|min:1|max:1000',
            'prefixe'  => 'nullable|string|max:12',
        ]);

        $prefixe = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $data['prefixe'] ?? ''));
        $genere = 0;

        for ($i = 0; $i < $data['quantite']; $i++) {
            // 4 blocs de 4 caractères
            $blocs = [];
            for ($b = 0; $b < 4; $b++) {
                $blocs[] = strtoupper(Str::random(4));
            }
            $cle = ($prefixe ? $prefixe . '-' : '') . implode('-', $blocs);

            if ($produit->clesLicence()->where('cle', $cle)->exists()) { $i--; continue; }

            $produit->clesLicence()->create(['cle' => $cle, 'statut' => 'disponible']);
            $genere++;
        }

        return back()->with('success', "$genere clé(s) générée(s).");
    }

    /** Supprime une clé encore disponible. */
    public function supprimer(CleLicence $cle)
    {
        $this->autoriser($cle->produit);

        abort_if($cle->statut !== 'disponible', 422, 'Une clé déjà attribuée ne peut pas être supprimée.');
        $cle->delete();

        return back()->with('success', 'Clé supprimée.');
    }
}
