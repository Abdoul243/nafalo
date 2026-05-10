<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Models\Utilisateur;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $statut = $request->get('statut', 'en_attente');
        $kycs = Kyc::with('utilisateur')
            ->when($statut !== 'tous', fn($q) => $q->where('statut', $statut))
            ->latest('soumis_le')
            ->paginate(20);

        $counts = [
            'en_attente' => Kyc::where('statut', Kyc::STATUT_EN_ATTENTE)->count(),
            'approuve'   => Kyc::where('statut', Kyc::STATUT_APPROUVE)->count(),
            'rejete'     => Kyc::where('statut', Kyc::STATUT_REJETE)->count(),
        ];

        return view('superadmin.kycs.index', compact('kycs', 'statut', 'counts'));
    }

    public function show(Kyc $kyc)
    {
        $kyc->load('utilisateur');
        return view('superadmin.kycs.show', compact('kyc'));
    }

    public function approuver(Kyc $kyc)
    {
        $kyc->update([
            'statut'    => Kyc::STATUT_APPROUVE,
            'traite_le' => now(),
            'traite_par'=> Auth::id(),
        ]);

        // Notifier le marchand (notification directe)
        \App\Models\NotificationMarchand::create([
            'utilisateur_id' => $kyc->utilisateur_id,
            'type'           => 'copub_reponse', // réutilisé pour simplifier
            'titre'          => '✅ KYC approuvé — Identité vérifiée !',
            'message'        => 'Félicitations ! Votre dossier KYC a été approuvé. Vous bénéficiez maintenant de toutes les fonctionnalités Nafalo.',
            'lien'           => null,
        ]);

        return back()->with('success', 'KYC approuvé avec succès.');
    }

    public function rejeter(Request $request, Kyc $kyc)
    {
        $request->validate(['note_admin' => 'required|string|max:500']);

        $kyc->update([
            'statut'     => Kyc::STATUT_REJETE,
            'note_admin' => $request->note_admin,
            'traite_le'  => now(),
            'traite_par' => Auth::id(),
        ]);

        \App\Models\NotificationMarchand::create([
            'utilisateur_id' => $kyc->utilisateur_id,
            'type'           => 'copub_reponse',
            'titre'          => '❌ KYC rejeté — Action requise',
            'message'        => 'Votre dossier KYC a été rejeté. Motif : ' . $request->note_admin . '. Veuillez soumettre un nouveau dossier avec des documents valides.',
            'lien'           => null,
        ]);

        return back()->with('success', 'KYC rejeté avec succès.');
    }

    public function telechargerDoc(Kyc $kyc, string $cote)
    {
        $path = $cote === 'verso' ? $kyc->document_verso : $kyc->document_recto;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404, 'Document introuvable.');
        }
        return Storage::disk('local')->download($path);
    }
}
