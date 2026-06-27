<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Moneroo\Laravel\Payment as MonerooPayment;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Transaction::where('boutique_id', $boutiqueId)
            ->with(['client', 'achats.produit']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('reference', 'like', "%{$q}%")
                    ->orWhereHas('client', fn($c) => $c->where('nom', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%"));
            });
        }

        $transactions = $query->latest()->paginate(20);
        
        $baseReussies = Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_REUSSI);

        $stats = [
            'total'       => Transaction::where('boutique_id', $boutiqueId)->count(),
            'reussies'    => (clone $baseReussies)->count(),
            'en_attente'  => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_EN_ATTENTE)->count(),
            'echouees'    => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_ECHOUE)->count(),
            'abandonnees' => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_ABANDONNE)->count(),
            // Répartition 95% / 5%
            'ca_total'    => (clone $baseReussies)->sum('montant_total'),
            'ca_marchand' => (clone $baseReussies)->sum('montant_marchand'),  // 95%
            'commissions' => (clone $baseReussies)->sum('commission'),         // 5%
        ];
        
        return view('admin.transactions.index', compact('transactions', 'stats'));
    }
    
    public function show(Transaction $transaction)
    {
        abort_if($transaction->boutique_id !== session('boutique_id'), 403);

        $transaction->load(['client', 'achats.produit']);

        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Synchronise le statut de toutes les transactions en_attente avec Moneroo.
     */
    public function syncMoneroo(Request $request)
    {
        $boutiqueId = session('boutique_id');

        $enAttente = Transaction::where('boutique_id', $boutiqueId)
            ->where('statut', Transaction::STATUT_EN_ATTENTE)
            ->whereNotNull('reference_paiement')
            ->get();

        if ($enAttente->isEmpty()) {
            return response()->json([
                'message' => 'Aucune transaction en attente à synchroniser.',
                'updated' => 0,
            ]);
        }

        $moneroo  = new MonerooPayment();
        $updated  = 0;
        $errors   = 0;
        $details  = [];

        foreach ($enAttente as $transaction) {
            try {
                $paymentData = $moneroo->verify($transaction->reference_paiement);
                $statusApi   = $paymentData->status ?? null;

                Log::info('Sync Moneroo', [
                    'ref'    => $transaction->reference,
                    'pay_id' => $transaction->reference_paiement,
                    'status' => $statusApi,
                ]);

                $nouveauStatut = null;

                if (in_array($statusApi, ['success', 'completed', 'paid'])) {
                    $nouveauStatut = Transaction::STATUT_REUSSI;
                } elseif (in_array($statusApi, ['failed', 'cancelled', 'expired'])) {
                    $nouveauStatut = Transaction::STATUT_ECHOUE;
                }

                if ($nouveauStatut) {
                    DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->update([
                            'statut'     => $nouveauStatut,
                            'updated_at' => now(),
                        ]);

                    // Livrer les produits si paiement réussi
                    if ($nouveauStatut === Transaction::STATUT_REUSSI) {
                        $transaction->refresh();
                        try {
                            app(\App\Http\Controllers\Boutique\CheckoutController::class)
                                ->livrerProduitsPublic($transaction);
                        } catch (\Throwable $e) {
                            Log::warning('Livraison échouée sync', ['id' => $transaction->id, 'err' => $e->getMessage()]);
                        }
                    }

                    $updated++;
                    $details[] = [
                        'reference' => $transaction->reference,
                        'ancien'    => 'en_attente',
                        'nouveau'   => $nouveauStatut,
                    ];
                }
            } catch (\Throwable $e) {
                $errors++;
                Log::warning('Sync Moneroo erreur', [
                    'ref'   => $transaction->reference,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message'  => "{$updated} transaction(s) mise(s) à jour, {$errors} erreur(s).",
            'updated'  => $updated,
            'errors'   => $errors,
            'details'  => $details,
            'total'    => $enAttente->count(),
        ]);
    }

    /**
     * Synchronise une seule transaction.
     */
    public function syncUne(Transaction $transaction)
    {
        abort_if($transaction->boutique_id !== session('boutique_id'), 403);

        if (!$transaction->reference_paiement) {
            return response()->json(['error' => 'Pas de référence Moneroo pour cette transaction.'], 422);
        }

        if ($transaction->statut !== Transaction::STATUT_EN_ATTENTE) {
            return response()->json(['message' => 'Transaction déjà traitée.', 'statut' => $transaction->statut]);
        }

        try {
            $moneroo     = new MonerooPayment();
            $paymentData = $moneroo->verify($transaction->reference_paiement);
            $statusApi   = $paymentData->status ?? null;

            $nouveauStatut = null;
            if (in_array($statusApi, ['success', 'completed', 'paid'])) {
                $nouveauStatut = Transaction::STATUT_REUSSI;
            } elseif (in_array($statusApi, ['failed', 'cancelled', 'expired'])) {
                $nouveauStatut = Transaction::STATUT_ECHOUE;
            }

            if ($nouveauStatut) {
                DB::table('transactions')->where('id', $transaction->id)
                    ->update(['statut' => $nouveauStatut, 'updated_at' => now()]);

                return response()->json([
                    'message' => 'Statut mis à jour.',
                    'statut'  => $nouveauStatut,
                    'label'   => $nouveauStatut === 'reussi' ? 'Réussi' : 'Échoué',
                ]);
            }

            return response()->json(['message' => 'Statut inchangé sur Moneroo.', 'statut_moneroo' => $statusApi]);

        } catch (\Throwable $e) {
            Log::error('Sync une transaction', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Erreur API Moneroo : ' . $e->getMessage()], 500);
        }
    }
}