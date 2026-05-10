<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['boutique', 'client'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $query->where('reference', 'like', '%' . $request->recherche . '%');
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $transactions = $query->paginate(20);

        $stats = [
            'total'      => Transaction::count(),
            'reussies'   => Transaction::where('statut', 'reussi')->count(),
            'en_attente' => Transaction::where('statut', 'en_attente')->count(),
            'echouees'   => Transaction::where('statut', 'echoue')->count(),
            'abandonnees' => Transaction::where('statut', 'abandonne')->count(),
            'chiffre_affaires' => Transaction::where('statut', 'reussi')->sum('montant_total'),
        ];

        return view('superadmin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['boutique', 'client', 'achats.produit']);

        return view('superadmin.transactions.show', compact('transaction'));
    }
}