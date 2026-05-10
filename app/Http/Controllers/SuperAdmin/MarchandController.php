<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MarchandController extends Controller
{
    public function index(Request $request)
    {
        $query = Utilisateur::where('role', 'admin')
            ->withCount('boutiques');

        if ($request->filled('recherche')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->recherche . '%')
                  ->orWhere('email', 'like', '%' . $request->recherche . '%');
            });
        }

        $marchands = $query->latest()->paginate(20);

        return view('superadmin.marchands.index', compact('marchands'));
    }

    public function show(Utilisateur $utilisateur)
    {
        $utilisateur->load('boutiques.produits');

        $totalVentes = Transaction::whereIn('boutique_id', $utilisateur->boutiques->pluck('id'))
            ->where('statut', 'reussi')
            ->sum('montant_total');

        $totalTransactions = Transaction::whereIn('boutique_id', $utilisateur->boutiques->pluck('id'))
            ->count();

        $dernieres_transactions = Transaction::whereIn('boutique_id', $utilisateur->boutiques->pluck('id'))
            ->with(['boutique', 'client'])
            ->latest()
            ->limit(10)
            ->get();

        return view('superadmin.marchands.show', compact(
            'utilisateur',
            'totalVentes',
            'totalTransactions',
            'dernieres_transactions'
        ));
    }

    public function toggle(Utilisateur $utilisateur)
    {
        // Activer / désactiver toutes les boutiques du marchand
        $utilisateur->boutiques()->update([
            'est_active' => !$utilisateur->boutiques()->first()?->est_active
        ]);

        $statut = $utilisateur->boutiques()->first()?->est_active ? 'activé' : 'désactivé';

        return back()->with('success', "Le compte de {$utilisateur->nom} a été {$statut}.");
    }

    public function contacter(Utilisateur $utilisateur)
    {
        return view('superadmin.marchands.contacter', compact('utilisateur'));
    }

    public function envoyerEmail(Utilisateur $utilisateur, Request $request)
    {
        $request->validate([
            'sujet'   => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($mail) use ($utilisateur, $request) {
                $mail->to($utilisateur->email, $utilisateur->nom)
                    ->from(config('mail.from.address'), 'Digital Store — Super Admin')
                    ->subject($request->sujet)
                    ->html("
                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;'>
                            <div style='background:#0f172a;padding:20px;border-radius:10px 10px 0 0;'>
                                <h2 style='color:white;margin:0;font-size:1.1rem;'>Digital Store</h2>
                                <p style='color:#f59e0b;margin:4px 0 0;font-size:0.8rem;'>Message de l'administration</p>
                            </div>
                            <div style='background:white;padding:24px;border:1px solid #e2e8f0;border-radius:0 0 10px 10px;'>
                                <p style='color:#374151;'>Bonjour <strong>{$utilisateur->nom}</strong>,</p>
                                <div style='color:#374151;line-height:1.7;white-space:pre-line;'>{$request->message}</div>
                                <hr style='border:none;border-top:1px solid #e2e8f0;margin:20px 0;'>
                                <p style='color:#94a3b8;font-size:0.8rem;'>
                                    Ce message vous a été envoyé par l'équipe Digital Store.
                                </p>
                            </div>
                        </div>
                    ");
            });

            return redirect()->route('superadmin.marchands.show', $utilisateur)
                ->with('success', "Email envoyé avec succès à {$utilisateur->nom}.");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage());
        }
    }
}