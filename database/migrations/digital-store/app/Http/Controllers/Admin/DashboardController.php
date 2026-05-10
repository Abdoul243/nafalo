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
            
        $totalClients = Client::where('boutique_id', $boutiqueId)->count();
        
        $totalProduits = Produit::where('boutique_id', $boutiqueId)->count();
        
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
        
        return view('admin.dashboard', compact(
            'totalVentes',
            'chiffreAffaires',
            'totalClients',
            'totalProduits',
            'ventesParJour',
            'topProduits',
            'dernieresTransactions',
            'paniersAbandonnes',
            'tauxConversion',
            'periode'
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