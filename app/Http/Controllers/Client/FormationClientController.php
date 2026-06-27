<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\Lecon;
use App\Models\ProgressionLecon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class FormationClientController extends Controller
{
    /** Lecteur de la formation (espace membre). */
    public function show(Produit $produit)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        if (!$client) {
            return redirect()->route('client.acces.demande')
                ->with('info', 'Connectez-vous pour accéder à votre formation.');
        }

        if (!$produit->estFormation() || $produit->boutique_id !== $boutique->id) {
            abort(404);
        }

        // Abonnement expiré → renvoyer vers "Mes achats" pour renouveler (pas un 403 brut).
        if ($produit->estAbonnement() && !$this->aAccquis($client, $produit)) {
            return redirect()->route('client.mes-achats.index')
                ->with('error', 'Votre abonnement à « ' . $produit->nom . ' » a expiré. Renouvelez pour y accéder.');
        }

        abort_unless($this->aAccquis($client, $produit), 403, "Vous n'avez pas accès à cette formation.");

        $produit->load(['modules.lecons']);

        // Leçons terminées par le client
        $lecIds = $produit->modules->flatMap->lecons->pluck('id');
        $terminees = ProgressionLecon::where('client_id', $client->id)
            ->whereIn('lecon_id', $lecIds)
            ->where('terminee', true)
            ->pluck('lecon_id')
            ->all();

        return view('boutique.client.formation', [
            'boutique'  => $boutique,
            'client'    => $client,
            'produit'   => $produit,
            'terminees' => $terminees,
        ]);
    }

    /** Marque une leçon comme terminée / non terminée. */
    public function terminerLecon(Request $request, Lecon $lecon)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        if (!$client) return response()->json(['error' => 'non_connecte'], 401);

        $produit = $lecon->module->produit;
        abort_unless($this->aAccquis($client, $produit), 403);

        $terminee = $request->boolean('terminee', true);

        ProgressionLecon::updateOrCreate(
            ['client_id' => $client->id, 'lecon_id' => $lecon->id],
            ['terminee' => $terminee, 'terminee_at' => $terminee ? now() : null]
        );

        return response()->json(['success' => true, 'terminee' => $terminee]);
    }

    /** Diffuse la vidéo uploadée (disque privé) avec support du seek (Range). */
    public function video(Lecon $lecon)
    {
        $this->verifierAccesLecon($lecon);

        abort_unless($lecon->video_fichier && Storage::disk('local')->exists($lecon->video_fichier), 404);

        // BinaryFileResponse gère automatiquement les requêtes Range (lecture/seek vidéo)
        return response()->file(Storage::disk('local')->path($lecon->video_fichier));
    }

    /** Télécharge la ressource d'une leçon (disque privé). */
    public function ressource(Lecon $lecon)
    {
        $this->verifierAccesLecon($lecon);

        abort_unless($lecon->ressource_fichier && Storage::disk('local')->exists($lecon->ressource_fichier), 404);

        return Storage::disk('local')->download($lecon->ressource_fichier, basename($lecon->ressource_fichier));
    }

    /* ── Helpers ─────────────────────────────────────────────────── */

    /** Vérifie l'accès à une leçon : aperçu gratuit OU formation achetée. */
    private function verifierAccesLecon(Lecon $lecon): void
    {
        if ($lecon->est_apercu) return;

        [$boutique, $client] = $this->boutiqueEtClient();
        abort_unless($client, 403);

        $produit = $lecon->module->produit;
        abort_unless($this->aAccquis($client, $produit), 403);
    }

    /** Le client a-t-il accès à ce produit ? (achat unique OU abonnement actif) */
    private function aAccquis(Client $client, Produit $produit): bool
    {
        // Produit en abonnement → l'accès dépend d'un abonnement encore actif.
        if ($produit->estAbonnement()) {
            return \App\Models\Abonnement::where('client_id', $client->id)
                ->where('produit_id', $produit->id)
                ->where('statut', 'actif')
                ->where('date_fin', '>=', now())
                ->exists();
        }

        // Paiement unique → un achat sur une transaction réussie suffit.
        return Achat::where('client_id', $client->id)
            ->where('produit_id', $produit->id)
            ->whereHas('transaction', fn($q) => $q->where('statut', 'reussi'))
            ->exists();
    }

    /** @return array{0: Boutique, 1: ?Client} */
    private function boutiqueEtClient(): array
    {
        $boutique = $this->getBoutique();
        $email    = Session::get('client_acces_' . $boutique->id);
        $client   = $email
            ? Client::where('boutique_id', $boutique->id)->where('email', $email)->first()
            : null;

        return [$boutique, $client];
    }

    private function getBoutique(): Boutique
    {
        $host = request()->getHost();
        $localHosts = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];

        if (!in_array($host, $localHosts, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }
        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }
        if (session('boutique_domaine')) {
            $b = Boutique::where('domaine_personnalise', session('boutique_domaine'))->where('est_active', true)->first();
            if ($b) return $b;
        }
        return Boutique::where('est_active', true)->firstOrFail();
    }
}
