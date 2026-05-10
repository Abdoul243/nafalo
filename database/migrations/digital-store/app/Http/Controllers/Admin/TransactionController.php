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
        
        $stats = [
            'total' => Transaction::where('boutique_id', $boutiqueId)->count(),
            'reussies' => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_REUSSI)->count(),
            'echouees' => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_ECHOUE)->count(),
            'abandonnees' => Transaction::where('boutique_id', $boutiqueId)->where('statut', Transaction::STATUT_ABANDONNE)->count(),
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