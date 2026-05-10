<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Boutique;
use App\Models\Transaction;
use App\Models\Produit;
use App\Models\Kyc;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_marchands'       => Utilisateur::where('role', 'admin')->count(),
            'total_boutiques'       => Boutique::count(),
            'boutiques_actives'     => Boutique::where('est_active', true)->count(),
            'total_transactions'    => Transaction::count(),
            'transactions_reussies' => Transaction::where('statut', 'reussi')->count(),
            'chiffre_affaires'      => Transaction::where('statut', 'reussi')->sum('montant_total'),
            'total_produits'        => Produit::count(),
            'total_commissions'     => Transaction::where('statut', 'reussi')->sum('commission'),
            'total_marchands_nets'  => Transaction::where('statut', 'reussi')->sum('montant_marchand'),
        ];

        // Dernières transactions
        $dernieres_transactions = Transaction::with(['boutique', 'client'])
            ->latest()
            ->limit(10)
            ->get();

        // Derniers marchands inscrits
        $derniers_marchands = Utilisateur::where('role', 'admin')
            ->latest()
            ->limit(5)
            ->get();

        // Évolution des transactions par jour (7 derniers jours)
        $evolution = Transaction::where('statut', 'reussi')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(montant_total) as montant')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── Feature 11 : Analytics avancés ───────────────────────────────
        // Revenus par mois (12 derniers mois)
        $revenusParMois = Transaction::where('statut', 'reussi')
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as mois, SUM(montant_total) as total, SUM(commission) as commissions, COUNT(*) as nb')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Top 5 marchands par CA
        $topMarchands = DB::table('transactions')
            ->join('boutiques', 'transactions.boutique_id', '=', 'boutiques.id')
            ->join('utilisateurs', 'boutiques.utilisateur_id', '=', 'utilisateurs.id')
            ->where('transactions.statut', 'reussi')
            ->select(
                'utilisateurs.id',
                'utilisateurs.nom',
                'utilisateurs.email',
                DB::raw('SUM(transactions.montant_total) as ca_total'),
                DB::raw('COUNT(transactions.id) as nb_ventes'),
                DB::raw('SUM(transactions.commission) as commissions')
            )
            ->groupBy('utilisateurs.id', 'utilisateurs.nom', 'utilisateurs.email')
            ->orderByDesc('ca_total')
            ->limit(5)
            ->get();

        // Taux de croissance (ce mois vs mois dernier)
        $caMoisCourant  = Transaction::where('statut', 'reussi')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('montant_total');
        $caMoisDernier  = Transaction::where('statut', 'reussi')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('montant_total');
        $tauxCroissance = $caMoisDernier > 0
            ? round((($caMoisCourant - $caMoisDernier) / $caMoisDernier) * 100, 1)
            : 0;

        // ── Feature 10 : Détection de fraude ─────────────────────────────
        $transactionsSuspectes = Transaction::where('est_suspicieux', true)
            ->with(['boutique', 'client'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $nbSuspects = Transaction::where('est_suspicieux', true)->count();

        // ── Feature 12 : KYC en attente ──────────────────────────────────
        $kycsEnAttente = Kyc::where('statut', Kyc::STATUT_EN_ATTENTE)
            ->with('utilisateur')
            ->latest('soumis_le')
            ->get();

        return view('superadmin.dashboard', compact(
            'stats',
            'dernieres_transactions',
            'derniers_marchands',
            'evolution',
            'revenusParMois',
            'topMarchands',
            'tauxCroissance',
            'caMoisCourant',
            'caMoisDernier',
            'transactionsSuspectes',
            'nbSuspects',
            'kycsEnAttente',
        ));
    }
}
