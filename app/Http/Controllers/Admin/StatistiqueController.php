<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Produit;
use App\Models\Achat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistiqueController extends Controller
{
    public function ventes(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $dateDebut = $request->get('date_debut', now()->subDays(30)->format('Y-m-d'));
        $dateFin = $request->get('date_fin', now()->format('Y-m-d'));
        
        // Statistiques globales
        $stats = [
            'total_ventes' => Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->count(),
                
            'chiffre_affaires' => Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->sum('montant_total'),
                
            'panier_moyen' => Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->whereBetween('created_at', [$dateDebut, $dateFin])
                ->avg('montant_total'),
        ];
        
        // Ventes par jour
        $ventesParJour = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(montant_total) as montant')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Produits les plus vendus
        $topProduits = Achat::whereHas('transaction', function($q) use ($boutiqueId, $dateDebut, $dateFin) {
                $q->where('boutique_id', $boutiqueId)
                  ->where('statut', Transaction::STATUT_REUSSI)
                  ->whereBetween('created_at', [$dateDebut, $dateFin]);
            })
            ->with('produit')
            ->select('produit_id', DB::raw('COUNT(*) as total_ventes'), DB::raw('SUM(montant) as ca_total'))
            ->groupBy('produit_id')
            ->orderByDesc('total_ventes')
            ->limit(10)
            ->get();

        // Top moyens de paiement
        $topMoyensPaiement = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereNotNull('moyen_paiement')
            ->select('moyen_paiement', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant_total) as ca'))
            ->groupBy('moyen_paiement')
            ->orderByDesc('total')
            ->get();

        // Top pays (déduit du code du moyen de paiement ex: orange_ci → CI)
        $topPays = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereNotNull('moyen_paiement')
            ->select('moyen_paiement', DB::raw('COUNT(*) as total'), DB::raw('SUM(montant_total) as ca'))
            ->groupBy('moyen_paiement')
            ->orderByDesc('total')
            ->get()
            ->groupBy(function($t) {
                $parts = explode('_', $t->moyen_paiement);
                return strtoupper(end($parts));
            })
            ->map(function($group, $code) {
                return [
                    'code'  => $code,
                    'total' => $group->sum('total'),
                    'ca'    => $group->sum('ca'),
                ];
            })
            ->sortByDesc('total')
            ->values();

        return view('admin.statistiques.ventes', compact(
            'stats',
            'ventesParJour',
            'topProduits',
            'topMoyensPaiement',
            'topPays',
            'dateDebut',
            'dateFin'
        ));
    }
    
    public function produits(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $produits = Produit::where('boutique_id', $boutiqueId)
            ->withCount(['achats' => function($q) {
                $q->whereHas('transaction', function($t) {
                    $t->where('statut', Transaction::STATUT_REUSSI);
                });
            }])
            ->withSum(['achats' => function($q) {
                $q->whereHas('transaction', function($t) {
                    $t->where('statut', Transaction::STATUT_REUSSI);
                });
            }], 'prix_unitaire')
            ->orderByDesc('achats_count')
            ->paginate(20);
            
        return view('admin.statistiques.produits', compact('produits'));
    }
}