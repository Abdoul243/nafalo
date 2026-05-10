<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FraudeController extends Controller
{
    public function index(Request $request)
    {
        // Charger les transactions suspectes existantes
        $suspectes = Transaction::where('est_suspicieux', true)
            ->with(['boutique', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Détecter automatiquement de nouvelles fraudes potentielles
        $this->detecterFraudes();

        // Statistiques fraude
        $stats = [
            'total_suspectes'     => Transaction::where('est_suspicieux', true)->count(),
            'montant_suspecte'    => Transaction::where('est_suspicieux', true)->sum('montant_total'),
            'echecs_recents'      => Transaction::where('statut', 'echoue')
                ->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('superadmin.fraudes.index', compact('suspectes', 'stats'));
    }

    public function marquer(Request $request, Transaction $transaction)
    {
        $request->validate(['raison' => 'required|string|max:300']);
        $transaction->update([
            'est_suspicieux'   => true,
            'raison_suspicion' => $request->raison,
        ]);
        return back()->with('success', 'Transaction marquée comme suspecte.');
    }

    public function blanchir(Transaction $transaction)
    {
        $transaction->update([
            'est_suspicieux'   => false,
            'raison_suspicion' => null,
        ]);
        return back()->with('success', 'Transaction blanchie (retirée de la liste des suspects).');
    }

    /* ── Détection automatique ────────────────────────────────────────── */

    private function detecterFraudes(): void
    {
        try {
            // Règle 1 : Plus de 5 transactions réussies par le même client en 1 heure
            $clientsRapides = DB::table('transactions')
                ->where('statut', 'reussi')
                ->where('created_at', '>=', now()->subHour())
                ->select('client_id', DB::raw('COUNT(*) as nb'))
                ->whereNotNull('client_id')
                ->groupBy('client_id')
                ->having('nb', '>', 5)
                ->pluck('client_id');

            if ($clientsRapides->isNotEmpty()) {
                Transaction::where('statut', 'reussi')
                    ->where('created_at', '>=', now()->subHour())
                    ->whereIn('client_id', $clientsRapides)
                    ->where('est_suspicieux', false)
                    ->update([
                        'est_suspicieux'   => true,
                        'raison_suspicion' => 'Auto: +5 transactions en 1h pour le même client',
                    ]);
            }

            // Règle 2 : Montant anormalement élevé (> 500 000 FCFA)
            Transaction::where('statut', 'reussi')
                ->where('montant_total', '>', 500000)
                ->where('est_suspicieux', false)
                ->update([
                    'est_suspicieux'   => true,
                    'raison_suspicion' => 'Auto: montant très élevé (> 500 000 FCFA)',
                ]);

            // Règle 3 : Même IP, plus de 3 transactions différentes en 30 min
            $ipsRapides = DB::table('transactions')
                ->where('statut', 'reussi')
                ->where('created_at', '>=', now()->subMinutes(30))
                ->whereNotNull('ip_client')
                ->select('ip_client', DB::raw('COUNT(DISTINCT client_id) as nb_clients'))
                ->groupBy('ip_client')
                ->having('nb_clients', '>', 3)
                ->pluck('ip_client');

            if ($ipsRapides->isNotEmpty()) {
                Transaction::where('statut', 'reussi')
                    ->where('created_at', '>=', now()->subMinutes(30))
                    ->whereIn('ip_client', $ipsRapides)
                    ->where('est_suspicieux', false)
                    ->update([
                        'est_suspicieux'   => true,
                        'raison_suspicion' => 'Auto: plusieurs clients depuis la même IP en 30 min',
                    ]);
            }

        } catch (\Throwable $e) {
            // Ne pas bloquer l'affichage
            \Log::warning('FraudeController detecterFraudes: ' . $e->getMessage());
        }
    }
}
