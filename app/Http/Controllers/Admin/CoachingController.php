<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\ReservationCoaching;
use Illuminate\Http\Request;

class CoachingController extends Controller
{
    private function autoriser(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    /** Liste des réservations + réglages de la séance. */
    public function reservations(Produit $produit)
    {
        $this->autoriser($produit);

        if (!$produit->estCoaching()) {
            $produit->update(['format' => 'coaching']);
        }

        $reservations = $produit->reservationsCoaching()
            ->with('client')
            ->orderByRaw("FIELD(statut,'en_attente','confirmee','annulee')")
            ->latest('date_souhaitee')
            ->paginate(30);

        return view('admin.coaching.reservations', compact('produit', 'reservations'));
    }

    /** Met à jour la durée, la pause et la disponibilité hebdomadaire. */
    public function reglages(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate([
            'coaching_duree' => 'nullable|integer|min:5|max:600',
            'coaching_pause' => 'nullable|integer|min:0|max:240',
            'jours'          => 'nullable|array',
        ]);

        $dispo = [];
        foreach (['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $j) {
            $val = $request->input("jours.$j");
            if (!empty($val['actif']) && !empty($val['debut']) && !empty($val['fin']) && $val['debut'] < $val['fin']) {
                $dispo[$j] = [['debut' => $val['debut'], 'fin' => $val['fin']]];
            }
        }

        $produit->update([
            'coaching_duree'          => $data['coaching_duree'] ?? 60,
            'coaching_pause'          => $data['coaching_pause'] ?? 0,
            'coaching_disponibilites' => $dispo ?: null,
        ]);

        return back()->with('success', 'Réglages enregistrés.');
    }

    /** Confirme une réservation (date + lien visio). */
    public function confirmer(Request $request, ReservationCoaching $reservation)
    {
        $this->autoriser($reservation->produit);

        $data = $request->validate([
            'date_confirmee' => 'required|date',
            'lien_visio'     => 'nullable|url|max:500',
        ]);

        $reservation->update([
            'date_confirmee' => $data['date_confirmee'],
            'lien_visio'     => $data['lien_visio'] ?? null,
            'statut'         => 'confirmee',
        ]);

        return back()->with('success', 'Réservation confirmée.');
    }

    /** Annule une réservation. */
    public function annuler(ReservationCoaching $reservation)
    {
        $this->autoriser($reservation->produit);
        $reservation->update(['statut' => 'annulee']);

        return back()->with('success', 'Réservation annulée.');
    }
}
