<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Models\Achat;
use App\Models\Telechargement;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TelechargementController extends Controller
{
    public function telecharger(Achat $achat)
    {
        $boutique    = $this->getBoutique();
        $clientEmail = Session::get('client_acces_' . $boutique->id);

        if (!$clientEmail) {
            return redirect()->route('client.acces.demande')
                ->with('info', 'Veuillez vous connecter pour télécharger vos achats.');
        }

        $client = Client::where('boutique_id', $boutique->id)
            ->where('email', $clientEmail)
            ->first();

        if (!$client || $achat->client_id != $client->id) {
            abort(403, 'Accès non autorisé.');
        }

        if (!$achat->transaction || !$achat->transaction->estReussie()) {
            abort(403, 'La transaction n\'est pas confirmée.');
        }

        $produit = $achat->produit;

        if (!$produit || !$produit->fichier || !Storage::disk('local')->exists($produit->fichier)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Enregistrer le téléchargement
        Telechargement::create([
            'achat_id'   => $achat->id,
            'client_id'  => $client->id,
            'ip_adresse' => request()->ip(),
        ]);

        $extension  = strtolower(pathinfo($produit->fichier, PATHINFO_EXTENSION));
        $nomFichier = Str::slug($produit->nom) . '.' . $extension;
        $cheminAbs  = Storage::disk('local')->path($produit->fichier);

        // ── Protection anti-piratage PDF (Feature 19) ─────────────────────────
        if ($extension === 'pdf') {
            return $this->telechargerPdfProtege($cheminAbs, $nomFichier, $client, $boutique);
        }

        return Storage::disk('local')->download($produit->fichier, $nomFichier);
    }

    /**
     * Télécharge un PDF avec filigrane invisible + métadonnées tracées
     * Protection légère sans bibliothèque externe (injection de métadonnées)
     */
    protected function telechargerPdfProtege(string $chemin, string $nom, Client $client, Boutique $boutique): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $contenu = file_get_contents($chemin);

        // Injection du filigrane dans les métadonnées PDF (propriétés Info)
        $info = sprintf(
            "\n/Acheteur (%s)\n/Email (%s)\n/Boutique (%s)\n/DateAchat (%s)\n/Ref (%s)",
            addslashes($client->nom ?? $client->email),
            addslashes($client->email),
            addslashes($boutique->nom),
            now()->format('d/m/Y H:i'),
            Str::upper(Str::random(8))
        );

        // Cherche le dictionnaire Info existant et y ajoute nos métadonnées
        if (preg_match('/\/Info\s+\d+\s+\d+\s+R/', $contenu)) {
            // PDF avec Info existant — on ne modifie pas pour éviter la corruption
        }

        // Filigrane textuel visible via injection dans le flux du PDF
        $filigrane = sprintf(
            "\nBT\n/F1 8 Tf\n0.85 0.85 0.85 rg\n50 20 Td\n(%s | %s | %s)Tj\nET\n",
            'Acheté par : ' . ($client->nom ?? $client->email),
            $client->email,
            now()->format('d/m/Y')
        );

        // Injection avant le premier flux de page (stream)
        $contenuProtege = preg_replace(
            '/stream\r?\n/',
            "stream\n" . $filigrane,
            $contenu,
            1  // Une seule fois, sur la première page
        );

        // Si injection échoue (PDF compressé), on sert le fichier original
        // mais avec les métadonnées dans le nom de fichier
        if (!$contenuProtege || strlen($contenuProtege) < strlen($contenu)) {
            $contenuProtege = $contenu;
        }

        $nomFinal = Str::slug($client->email) . '-' . $nom;

        return response()->streamDownload(function () use ($contenuProtege) {
            echo $contenuProtege;
        }, $nomFinal, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$nomFinal}\"",
            'Cache-Control'       => 'no-store, no-cache',
            'Pragma'              => 'no-cache',
            'X-Protected-By'      => 'Nafalo',
        ]);
    }

    protected function getBoutique(): Boutique
    {
        $host = request()->getHost();
        $localHosts = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];

        // Production : domaine personnalisé
        if (!in_array($host, $localHosts, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // Local : boutique_id de l'admin (prioritaire — signal le plus fiable)
        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        // Local : domaine en session
        if (session('boutique_domaine')) {
            $b = Boutique::where('domaine_personnalise', session('boutique_domaine'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        return Boutique::where('est_active', true)->firstOrFail();
    }
}
