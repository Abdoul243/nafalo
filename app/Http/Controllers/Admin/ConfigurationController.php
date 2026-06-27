<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigurationBoutique;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    public function index()
    {
        return view('admin.configurations.index');
    }
    
    public function general(Request $request)
    {
        $boutique = $this->resolveBoutique();
        $boutiqueId = $boutique->id;
        
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'email' => 'required|email|max:255',
                'telephone' => 'nullable|string|max:50',
                'reseaux_sociaux' => 'nullable|array',
                'domaine_personnalise' => 'nullable|string|max:191|unique:boutiques,domaine_personnalise,' . $boutiqueId,
            ]);
            
            // ✅ Stocker le logo en fichier (pas en binaire)
            if ($request->hasFile('logo')) {
                $request->validate(['logo' => 'image|max:2048']);
                $logo = $request->file('logo');
                
                // Supprimer l'ancien logo si existe
                if ($boutique->logo) {
                    Storage::disk('public')->delete($boutique->logo);
                }
                
                $validated['logo'] = $logo->store('boutiques/logos', 'public');
                $validated['logo_mime'] = $logo->getMimeType();
                $validated['logo_taille'] = $logo->getSize();
            }
            
            $boutique->update($validated);
            
            return redirect()->route('admin.configurations.general')
                ->with('success', 'Configuration générale mise à jour avec succès.');
        }
        
        return view('admin.configurations.general', compact('boutique'));
    }
    
    public function paiement(Request $request)
    {
        $boutiqueId = $this->resolveBoutique()->id;
        $configuration = ConfigurationBoutique::firstOrCreate(
            ['boutique_id' => $boutiqueId],
            ['devise' => 'XOF', 'relance_delai_jours' => 3]
        );
        
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'passerelle_paiement' => 'required|string|max:100',
                'cle_api_paiement' => 'nullable|string|max:191',
                'secret_api_paiement' => 'nullable|string|max:191',
                'devise' => 'required|string|size:3',
            ]);
            
            $configuration->update($validated);
            
            return redirect()->route('admin.configurations.paiement')
                ->with('success', 'Configuration de paiement mise à jour avec succès.');
        }
        
        return view('admin.configurations.paiement', compact('configuration'));
    }
    
    public function email(Request $request)
    {
        $boutiqueId = $this->resolveBoutique()->id;
        $configuration = ConfigurationBoutique::firstOrCreate(
            ['boutique_id' => $boutiqueId],
            ['devise' => 'XOF', 'relance_delai_jours' => 3]
        );
        
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'email_expediteur' => 'required|email|max:191',
                'email_template_achat' => 'nullable|string',
                'email_template_relance' => 'nullable|string',
                'relance_delai_jours' => 'required|integer|min:1|max:30',
            ]);
            
            $configuration->update($validated);
            
            return redirect()->route('admin.configurations.email')
                ->with('success', 'Configuration email mise à jour avec succès.');
        }
        
        return view('admin.configurations.email', compact('configuration'));
    }

    /**
     * Page Apparence — thème, couleurs, WhatsApp, devise, langue (Features 14, 16, 23, 24)
     */
    public function apparence(Request $request)
    {
        $boutique      = $this->resolveBoutique();
        $boutiqueId    = $boutique->id;
        $configuration = \App\Models\ConfigurationBoutique::firstOrCreate(
            ['boutique_id' => $boutiqueId],
            ['devise' => 'XOF', 'relance_delai_jours' => 3]
        );

        if ($request->isMethod('post')) {
            $request->validate([
                'theme'       => 'nullable|string|in:light,dark,blue,green,orange',
                'couleur'     => 'nullable|string|max:7',
                'whatsapp'    => 'nullable|string|max:20',
                'devise'      => 'nullable|string|max:5',
                'langue'      => 'nullable|string|in:fr,en,ar,pt',
            ]);

            // Sauvegarder dans reseaux_sociaux pour WhatsApp
            $reseaux = $boutique->reseaux_sociaux ?? [];
            $reseaux['whatsapp'] = $request->whatsapp;
            $boutique->update(['reseaux_sociaux' => $reseaux]);

            // Sauvegarder dans configuration
            $configuration->update([
                'devise'  => $request->devise ?? 'XOF',
                'theme'   => $request->theme ?? 'light',
                'couleur' => $request->couleur ?? '#2563eb',
                'langue'  => $request->langue ?? 'fr',
            ]);

            return redirect()->route('admin.configurations.apparence')
                ->with('success', 'Apparence et paramètres régionaux mis à jour avec succès.');
        }

        return view('admin.configurations.apparence', compact('boutique', 'configuration'));
    }

    private function resolveBoutique(): Boutique
    {
        $boutiqueId = session('boutique_id');

        if ($boutiqueId) {
            $boutique = Boutique::find($boutiqueId);
            if ($boutique) {
                return $boutique;
            }
        }

        $boutique = Boutique::query()->orderBy('id')->firstOrFail();
        session(['boutique_id' => $boutique->id]);

        return $boutique;
    }
}