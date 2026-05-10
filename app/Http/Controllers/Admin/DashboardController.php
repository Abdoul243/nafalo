<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        // Statistiques globales
        $totalVentes = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->count();
            
        $chiffreAffaires = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->sum('montant_total');

        // Gains nets du marchand (après commission 5%)
        $gainsNets = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->sum('montant_marchand');

        // Total commissions reversées à la plateforme
        $totalCommissions = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->sum('commission');
            
        $totalClients = Client::where('boutique_id', $boutiqueId)->count();
        
        $totalProduits = Produit::where('boutique_id', $boutiqueId)->count();

        // ── Lead Magnet ──────────────────────────────────────────────
        // Nombre total de leads capturés (achats à 0 FCFA sur produits gratuits)
        $totalLeads = \App\Models\Achat::whereHas('produit', fn($q) =>
                $q->where('boutique_id', $boutiqueId)->where('type', 'gratuit')
            )
            ->where('montant', 0)
            ->distinct('client_id')
            ->count('client_id');

        // Produits lead magnet actifs
        $produitsLeadMagnet = Produit::where('boutique_id', $boutiqueId)
            ->where('type', 'gratuit')
            ->where('est_publie', true)
            ->withCount(['achats as nb_leads' => fn($q) => $q->where('montant', 0)])
            ->get();
        
        // Ventes par période
        $periode = $request->get('periode', '30jours');
        $ventesParJour = $this->getVentesParPeriode($boutiqueId, $periode);
        
        // Produits les plus vendus
        $topProduits = Produit::where('boutique_id', $boutiqueId)
            ->withCount('achats')
            ->orderBy('achats_count', 'desc')
            ->limit(5)
            ->get();
            
        // Dernières transactions
        $dernieresTransactions = Transaction::where('boutique_id', $boutiqueId)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Statistiques des paniers
        $paniersAbandonnes = DB::table('paniers_abandonnes')
            ->where('boutique_id', $boutiqueId)
            ->count();

        $tauxConversion = $this->calculerTauxConversion($boutiqueId);

        // Ventes par catégorie (pour le graphique donut - Feature 1)
        $ventesParCategorie = DB::table('achats')
            ->join('produits', 'achats.produit_id', '=', 'produits.id')
            ->join('transactions', 'achats.transaction_id', '=', 'transactions.id')
            ->leftJoin('categories', 'produits.categorie_id', '=', 'categories.id')
            ->where('produits.boutique_id', $boutiqueId)
            ->where('transactions.statut', Transaction::STATUT_REUSSI)
            ->select(
                DB::raw('COALESCE(categories.nom, "Sans catégorie") as nom'),
                DB::raw('COUNT(achats.id) as nb_ventes'),
                DB::raw('SUM(transactions.montant_total) as total')
            )
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('nb_ventes')
            ->limit(6)
            ->get();

        // Nouveaux clients ce mois-ci
        $nouveauxClients = Client::where('boutique_id', $boutiqueId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Produits publiés vs total
        $produitPublies = Produit::where('boutique_id', $boutiqueId)->where('est_publie', true)->count();

        return view('admin.dashboard', compact(
            'totalVentes',
            'chiffreAffaires',
            'gainsNets',
            'totalCommissions',
            'totalClients',
            'totalProduits',
            'ventesParJour',
            'topProduits',
            'dernieresTransactions',
            'paniersAbandonnes',
            'tauxConversion',
            'periode',
            'ventesParCategorie',
            'nouveauxClients',
            'produitPublies',
            'totalLeads',
            'produitsLeadMagnet'
        ));
    }
    
    private function getVentesParPeriode($boutiqueId, $periode)
    {
        $query = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI);
            
        switch ($periode) {
            case '7jours':
                $query->whereDate('created_at', '>=', now()->subDays(7));
                break;
            case '30jours':
                $query->whereDate('created_at', '>=', now()->subDays(30));
                break;
            case '12mois':
                $query->whereDate('created_at', '>=', now()->subMonths(12));
                break;
        }
        
        return $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_ventes'),
                DB::raw('SUM(montant_total) as total_montant')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    private function calculerTauxConversion($boutiqueId)
    {
        $totalPaniers = DB::table('paniers_abandonnes')
            ->where('boutique_id', $boutiqueId)
            ->count();
            
        $totalVentes = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_REUSSI)
            ->count();
            
        if ($totalPaniers + $totalVentes == 0) {
            return 0;
        }
        
        return round(($totalVentes / ($totalPaniers + $totalVentes)) * 100, 2);
    }
}