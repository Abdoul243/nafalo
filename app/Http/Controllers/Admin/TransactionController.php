<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $boutiqueId = session('boutique_id');
        
        $query = Transaction::where('boutique_id', $boutiqueId)
            ->with('client');
            
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->has('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        $transactions = $query->latest()->paginate(20);
        
        $baseReussies = Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_REUSSI);

        $stats = [
            'total'       => Transaction::where('boutique_id', $boutiqueId)->count(),
            'reussies'    => (clone $baseReussies)->count(),
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
        $this->authorize('view', $transaction);
        
        $transaction->load(['client', 'achats.produit']);
        
        return view('admin.transactions.show', compact('transaction'));
    }
}