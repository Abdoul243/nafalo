<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\ReservationCoaching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CoachingController extends Controller
{
    /** Page de réservation d'une séance. */
    public function reserver(Produit $produit)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        if (!$client) {
            return redirect()->route('client.acces.demande');
        }

        if (!$produit->estCoaching() || $produit->boutique_id !== $boutique->id) {
            abort(404);
        }
        abort_unless($this->aAchete($client, $produit), 403);

        $reservations = ReservationCoaching::where('produit_id', $produit->id)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        return view('boutique.client.coaching', compact('boutique', 'client', 'produit', 'reservations'));
    }

    /** Le client demande un créneau. */
    public function store(Request $request, Produit $produit)
    {
        [$boutique, $client] = $this->boutiqueEtClient();
        abort_unless($client, 403);
        abort_unless($produit->estCoaching() && $produit->boutique_id === $boutique->id, 404);
        abort_unless($this->aAchete($client, $produit), 403);

        $data = $request->validate([
            'date_souhaitee' => 'required|date|after:now',
            'message'        => 'nullable|string|max:1000',
        ]);

        $achat = Achat::where('client_id', $client->id)->where('produit_id', $produit->id)
            ->whereHas('transaction', fn($q) => $q->where('statut', 'reussi'))->latest()->first();

        ReservationCoaching::create([
            'produit_id'     => $produit->id,
            'client_id'      => $client->id,
            'achat_id'       => $achat->id ?? null,
            'date_souhaitee' => $data['date_souhaitee'],
            'statut'         => 'en_attente',
            'message'        => $data['message'] ?? null,
        ]);

        return back()->with('success', 'Demande de réservation envoyée. Le coach va la confirmer.');
    }

    /* ── Helpers ─────────────────────────────────────────────────── */

    private function aAchete(Client $client, Produit $produit): bool
    {
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
