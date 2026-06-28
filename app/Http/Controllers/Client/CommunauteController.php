<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\Abonnement;
use App\Models\MessageCommunaute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommunauteController extends Controller
{
    /** Espace communauté (fil de discussion). */
    public function show(Produit $produit)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        if (!$client) {
            return redirect()->route('client.acces.demande')
                ->with('info', 'Connectez-vous pour accéder à la communauté.');
        }

        if (!$produit->estCommunaute() || $produit->boutique_id !== $boutique->id) {
            abort(404);
        }

        // Abonnement expiré → renouveler
        if ($produit->estAbonnement() && !$this->aAcces($client, $produit)) {
            return redirect()->route('client.mes-achats.index')
                ->with('error', 'Votre accès à « ' . $produit->nom . ' » a expiré. Renouvelez pour y accéder.');
        }

        abort_unless($this->aAcces($client, $produit), 403);

        $messages = $produit->messagesCommunaute()
            ->with('client')
            ->latest()
            ->paginate(30);

        return view('boutique.client.communaute', compact('boutique', 'client', 'produit', 'messages'));
    }

    /** Un membre publie un message. */
    public function poster(Request $request, Produit $produit)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        abort_unless($client, 403);
        abort_unless($produit->estCommunaute() && $produit->boutique_id === $boutique->id, 404);
        abort_unless($this->aAcces($client, $produit), 403);

        $data = $request->validate(['contenu' => 'required|string|max:2000']);

        MessageCommunaute::create([
            'produit_id'   => $produit->id,
            'client_id'    => $client->id,
            'est_marchand' => false,
            'nom_auteur'   => $client->nom ?: $client->email,
            'contenu'      => $data['contenu'],
        ]);

        return back()->with('success', 'Message publié.');
    }

    /* ── Helpers ─────────────────────────────────────────────────── */

    private function aAcces(Client $client, Produit $produit): bool
    {
        if ($produit->estAbonnement()) {
            return Abonnement::where('client_id', $client->id)
                ->where('produit_id', $produit->id)
                ->where('statut', 'actif')
                ->where('date_fin', '>=', now())
                ->exists();
        }

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
