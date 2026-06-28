<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\MessageCommunaute;
use Illuminate\Http\Request;

class CommunauteController extends Controller
{
    private function autoriser(Produit $produit): void
    {
        abort_if($produit->boutique_id !== session('boutique_id'), 403);
    }

    /** Page de gestion de la communauté (annonces + modération). */
    public function gestion(Produit $produit)
    {
        $this->autoriser($produit);

        if (!$produit->estCommunaute()) {
            $produit->update(['format' => 'communaute']);
        }

        $messages = $produit->messagesCommunaute()->with('client')->latest()->paginate(30);

        // Nombre de membres (achats réussis ou abonnés actifs)
        $membres = \App\Models\Achat::where('produit_id', $produit->id)
            ->whereHas('transaction', fn($q) => $q->where('statut', 'reussi'))
            ->distinct('client_id')->count('client_id');

        return view('admin.communautes.gestion', compact('produit', 'messages', 'membres'));
    }

    /** Le marchand publie une annonce. */
    public function poster(Request $request, Produit $produit)
    {
        $this->autoriser($produit);

        $data = $request->validate(['contenu' => 'required|string|max:2000']);

        MessageCommunaute::create([
            'produit_id'   => $produit->id,
            'client_id'    => null,
            'est_marchand' => true,
            'nom_auteur'   => $produit->boutique->nom ?? 'Équipe',
            'contenu'      => $data['contenu'],
        ]);

        return back()->with('success', 'Annonce publiée.');
    }

    /** Supprime un message (modération). */
    public function supprimer(MessageCommunaute $message)
    {
        $this->autoriser($message->produit);
        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}
