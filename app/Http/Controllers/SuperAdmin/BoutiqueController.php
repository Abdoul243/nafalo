<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Transaction;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class BoutiqueController extends Controller
{
    public function index(Request $request)
    {
        $query = Boutique::with('utilisateur')
            ->withCount(['produits', 'clients', 'transactions']);

        if ($request->filled('recherche')) {
            $query->where('nom', 'like', '%' . $request->recherche . '%');
        }

        if ($request->filled('statut')) {
            $query->where('est_active', $request->statut === 'active');
        }

        $boutiques = $query->latest()->paginate(20);

        return view('superadmin.boutiques.index', compact('boutiques'));
    }

    public function show(Boutique $boutique)
    {
        $boutique->load(['utilisateur', 'produits', 'clients']);
        $marchands = Utilisateur::where('role', 'admin')
            ->where('id', '!=', $boutique->utilisateur_id)
            ->orderBy('nom')
            ->get(['id', 'nom', 'email']);

        $stats = [
            'total_produits'     => $boutique->produits()->count(),
            'total_clients'      => $boutique->clients()->count(),
            'total_transactions' => $boutique->transactions()->count(),
            'chiffre_affaires'   => $boutique->transactions()
                ->where('statut', 'reussi')
                ->sum('montant_total'),
        ];

        $dernieres_transactions = $boutique->transactions()
            ->with('client')
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.boutiques.show', compact(
            'boutique',
            'stats',
            'dernieres_transactions',
            'marchands'
        ));
    }

    public function reassigner(Request $request, Boutique $boutique)
    {
        $request->validate([
            'nouveau_marchand_id' => 'required|exists:utilisateurs,id',
        ]);

        $nouveauMarchand = Utilisateur::where('id', $request->nouveau_marchand_id)
            ->where('role', 'admin')
            ->firstOrFail();

        $ancienNom = $boutique->utilisateur->nom ?? '—';
        $boutique->update(['utilisateur_id' => $nouveauMarchand->id]);

        return back()->with('success',
            "La boutique « {$boutique->nom} » a été réassignée de {$ancienNom} vers {$nouveauMarchand->nom}."
        );
    }

    public function toggle(Boutique $boutique)
    {
        $boutique->update(['est_active' => !$boutique->est_active]);

        $statut = $boutique->est_active ? 'activée' : 'désactivée';

        return back()->with('success', "La boutique {$boutique->nom} a été {$statut}.");
    }
}