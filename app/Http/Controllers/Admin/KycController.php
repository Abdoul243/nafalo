<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /** Afficher le formulaire + statut KYC du marchand */
    public function index()
    {
        $kyc = Kyc::firstOrNew(['utilisateur_id' => Auth::id()]);
        return view('admin.kyc.index', compact('kyc'));
    }

    /** Soumettre les documents */
    public function soumettre(Request $request)
    {
        $request->validate([
            'type_document'  => 'required|in:cni,passeport,permis',
            'document_recto' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'document_verso' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'document_recto.required' => 'Veuillez fournir le recto de votre document.',
            'document_recto.max'      => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        $kyc = Kyc::firstOrNew(['utilisateur_id' => Auth::id()]);

        // Empêcher re-soumission si déjà approuvé
        if ($kyc->exists && $kyc->statut === Kyc::STATUT_APPROUVE) {
            return back()->with('error', 'Votre KYC est déjà approuvé.');
        }

        // Supprimer les anciens fichiers
        if ($kyc->document_recto) Storage::delete($kyc->document_recto);
        if ($kyc->document_verso) Storage::delete($kyc->document_verso);

        $recto = $request->file('document_recto')->store('kyc/' . Auth::id(), 'local');
        $verso = $request->hasFile('document_verso')
            ? $request->file('document_verso')->store('kyc/' . Auth::id(), 'local')
            : null;

        $kyc->fill([
            'utilisateur_id' => Auth::id(),
            'statut'         => Kyc::STATUT_EN_ATTENTE,
            'type_document'  => $request->type_document,
            'document_recto' => $recto,
            'document_verso' => $verso,
            'note_admin'     => null,
            'soumis_le'      => now(),
            'traite_le'      => null,
            'traite_par'     => null,
        ])->save();

        return back()->with('success', 'Votre dossier KYC a été soumis avec succès. Notre équipe le traitera sous 24-48h.');
    }
}
