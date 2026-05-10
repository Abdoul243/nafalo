<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Achat;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Upsell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LeadController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }

    /**
     * Traite le formulaire de capture d'un lead magnet.
     * Crée le client, l'achat, envoie le fichier par email.
     */
    public function capturer(Request $request, Produit $produit)
    {
        // Vérifications de sécurité
        if ($produit->boutique_id !== $this->boutique->id || !$produit->est_publie) {
            abort(404);
        }

        if (!$produit->estGratuit()) {
            return redirect()->route('boutique.produit.show', $produit->slug)
                ->with('error', 'Ce produit n\'est pas gratuit.');
        }

        if ($produit->limiteAtteinte()) {
            return redirect()->route('boutique.produit.show', $produit->slug)
                ->with('error', 'Désolé, ce produit gratuit n\'est plus disponible.');
        }

        // Validation des champs
        $regles = [
            'nom'   => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        foreach ($produit->champsLeadActifs() as $champ) {
            $regles[$champ] = 'nullable|string|max:255';
        }

        $donnees = $request->validate($regles);

        // Créer ou retrouver le client
        $client = Client::firstOrCreate(
            ['email' => $donnees['email'], 'boutique_id' => $this->boutique->id],
            ['nom' => $donnees['nom'], 'telephone' => $donnees['telephone'] ?? null]
        );

        // Mettre à jour les données supplémentaires si présentes
        $updates = [];
        if (!empty($donnees['telephone'])) $updates['telephone'] = $donnees['telephone'];
        if ($updates) $client->update($updates);

        // Vérifier si ce client a déjà récupéré ce produit gratuit
        $dejaTelechargé = Achat::where('client_id', $client->id)
            ->where('produit_id', $produit->id)
            ->exists();

        // Générer l'achat (ou réutiliser l'existant)
        if (!$dejaTelechargé) {
            $achat = Achat::create([
                'transaction_id'        => null,
                'client_id'             => $client->id,
                'produit_id'            => $produit->id,
                'boutique_id'           => $this->boutique->id,
                'montant'               => 0,
                'prix_unitaire'         => 0,
                'quantite'              => 1,
                'token_telechargement'  => Str::random(40),
            ]);

            // Incrémenter le compteur de téléchargements
            $produit->increment('lead_compteur');

        } else {
            $achat = Achat::where('client_id', $client->id)
                ->where('produit_id', $produit->id)
                ->first();
        }

        // Connecter automatiquement le client en session
        Session::put('client_acces_' . $this->boutique->id, $client->email);

        // Envoyer l'email avec le lien de téléchargement + upsells
        try {
            $emailService = new \App\Services\Email\EmailService();
            $emailService->envoyerLeadMagnet($client, $produit, $achat, $this->boutique);
        } catch (\Exception $e) {
            Log::error('Erreur envoi email lead magnet', ['error' => $e->getMessage()]);
        }

        // Notification in-app au marchand
        try {
            $utilisateurId = $this->boutique->utilisateur_id;
            if ($utilisateurId) {
                \App\Services\NotificationService::nouveauLead($utilisateurId, $produit->nom, $client);
            }
        } catch (\Throwable $e) { /* silencieux */ }

        // Charger les upsells actifs pour la page de remerciement
        $upsells = Upsell::where('produit_id', $produit->id)
            ->where('est_actif', true)
            ->with('produitUpsell')
            ->orderBy('ordre')
            ->get();

        return view('boutique.lead.merci', [
            'boutique' => $this->boutique,
            'produit'  => $produit,
            'client'   => $client,
            'achat'    => $achat,
            'upsells'  => $upsells,
        ]);
    }
}
