<?php

namespace App\Http\Controllers\Boutique;

use App\Models\Produit;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Upsell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CheckoutController extends BoutiqueBaseController
{
    protected $boutique;

    public function __construct(Request $request)
    {
        $this->boutique = $this->resolveBoutique($request);
    }

    /**
     * Affiche le formulaire checkout (infos client)
     */
    public function informations()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);

        if (empty($panier)) {
            return redirect()->route('boutique.panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        $produits = Produit::whereIn('id', array_keys($panier))->get();
        $total = $produits->sum('prix');

        return view('boutique.checkout.informations', [
            'boutique'  => $this->boutique,
            'produits'  => $produits,
            'panier'    => $panier,
            'total'     => $total,
        ]);
    }

    /**
     * Initie le paiement via GeniusPay → redirige vers checkout GeniusPay
     */
    public function initierPaiement(Request $request)
    {
        $request->validate([
            'nom'      => 'required|string|max:255',
            'email'    => 'required|email',
            'telephone' => 'nullable|string|max:20',
        ]);

        $panier = Session::get('panier_' . $this->boutique->id, []);

        if (empty($panier)) {
            return redirect()->route('boutique.panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        $produits = Produit::whereIn('id', array_keys($panier))->get();
        $total    = $produits->sum('prix');

        // Créer ou retrouver le client
        $client = Client::firstOrCreate(
            ['email' => $request->email, 'boutique_id' => $this->boutique->id],
            ['nom' => $request->nom, 'telephone' => $request->telephone]
        );

        // Générer une référence unique
        $reference = 'DS-' . strtoupper(Str::random(8));

        // Calculer la commission (5%) et le montant net du marchand (95%)
        $commission      = round($total * 0.05, 2);
        $montantMarchand = round($total - $commission, 2);

        // Créer la transaction en base (en attente)
        $transaction = Transaction::create([
            'reference'        => $reference,
            'boutique_id'      => $this->boutique->id,
            'client_id'        => $client->id,
            'montant_total'    => $total,
            'commission'       => $commission,
            'montant_marchand' => $montantMarchand,
            'statut'           => 'en_attente',
            'details'          => $produits->map(fn($p) => [
                'produit_id' => $p->id,
                'nom'        => $p->nom,
                'prix'       => $p->prix,
            ])->toArray(),
        ]);

        // Sauvegarder infos client en session
        Session::put('checkout_client_' . $this->boutique->id, [
            'nom'       => $request->nom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
        ]);
        Session::put('checkout_transaction_' . $this->boutique->id, $transaction->id);

        // Appel API GeniusPay
        try {
            $response = Http::withHeaders([
                'X-API-Key'    => config('services.geniuspay.key'),
                'X-API-Secret' => config('services.geniuspay.secret'),
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->post(config('services.geniuspay.url') . '/payments', [
                'amount'      => (int) $total,
                'description' => 'Commande ' . $reference . ' — ' . $this->boutique->nom,
                'currency'    => 'XOF',
                'customer'    => [
                    'name'  => $request->nom,
                    'email' => $request->email,
                    'phone' => $request->telephone ?? '',
                ],
                'metadata'    => [
                    'reference'      => $reference,
                    'transaction_id' => $transaction->id,
                    'boutique_id'    => $this->boutique->id,
                ],
                'callback_url' => route('boutique.checkout.callback'),
                'success_url'  => route('boutique.checkout.succes'),
                'cancel_url'   => route('boutique.checkout.annulation'),
            ]);

            if ($response->successful() && $response->json('success')) {
                $data         = $response->json('data');
                $checkoutUrl  = $data['checkout_url'] ?? $data['payment_url'] ?? null;
                $gpReference  = $data['reference'] ?? null;

                // Sauvegarder la référence GeniusPay
                $transaction->update([
                    'reference_paiement' => $gpReference,
                ]);

                if ($checkoutUrl) {
                    return redirect()->away($checkoutUrl);
                }
            }

            Log::error('GeniusPay initiation échouée', [
                'response' => $response->json(),
                'status'   => $response->status(),
            ]);

            return back()->with('error', 'Erreur lors de l\'initialisation du paiement. Veuillez réessayer.');

        } catch (\Exception $e) {
            Log::error('GeniusPay exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'Erreur de connexion au service de paiement.');
        }
    }

    /**
     * Page de succès — le client revient après paiement réussi
     */
    public function succes(Request $request)
    {
        $transactionId = Session::get('checkout_transaction_' . $this->boutique->id);
        $transaction   = $transactionId ? Transaction::find($transactionId) : null;

        // Si pas trouvée via session, chercher via référence GeniusPay dans l'URL
        if (!$transaction) {
            $reference = $request->get('reference');
            if ($reference) {
                $transaction = Transaction::where('reference_paiement', $reference)
                    ->orWhere('reference', $reference)
                    ->first();
            }
        }

        // Confirmer le paiement si encore en attente
        if ($transaction && $transaction->statut !== 'reussi') {
            $transaction->update([
                'statut'             => 'reussi',
                'reference_paiement' => $request->get('reference') ?? $transaction->reference_paiement,
                'moyen_paiement'     => 'geniuspay',
            ]);

            // Créer les achats
            $this->livrerProduits($transaction);

            // Envoyer email de confirmation au client + notification au marchand
            try {
                $emailService = new \App\Services\Email\EmailService();
                $transaction->load(['achats.produit', 'client', 'boutique.utilisateur', 'boutique.configuration']);
                $emailService->envoyerFichiersAchat($transaction);
                $emailService->envoyerNotificationVente($transaction);
                // Notification in-app
                \App\Services\NotificationService::nouvelleVente($transaction);
            } catch (\Exception $e) {
                Log::error('Erreur envoi email confirmation', ['error' => $e->getMessage()]);
            }
        }

        // Récupérer les achats
        $achats = collect();
        if ($transaction) {
            $achats = \App\Models\Achat::where('transaction_id', $transaction->id)
                ->with('produit')
                ->get();

            // Connecter automatiquement le client
            if ($transaction->client) {
                Session::put('client_acces_' . $this->boutique->id, $transaction->client->email);
            }
        }

        // Vider le panier
        Session::forget('panier_' . $this->boutique->id);
        Session::forget('checkout_transaction_' . $this->boutique->id);

        // Charger les upsells actifs pour les produits achetés
        $upsells = collect();
        if ($achats->isNotEmpty()) {
            $produitIds = $achats->pluck('produit_id')->filter()->unique();
            $upsells = Upsell::whereIn('produit_id', $produitIds)
                ->where('est_actif', true)
                ->with('produitUpsell')
                ->orderBy('ordre')
                ->get()
                // Exclure les produits que le client vient déjà d'acheter
                ->reject(fn($u) => $produitIds->contains($u->produit_upsell_id));
        }

        return view('boutique.checkout.succes', [
            'boutique'    => $this->boutique,
            'transaction' => $transaction,
            'achats'      => $achats,
            'upsells'     => $upsells,
        ]);
    }

    /**
     * Page d'annulation
     */
    public function annulation()
    {
        return view('boutique.checkout.annulation', [
            'boutique' => $this->boutique,
        ]);
    }

    /**
     * Webhook GeniusPay — appelé par GeniusPay après paiement
     */
    public function webhook(Request $request)
    {
        // Vérifier la signature webhook (sécurité)
        $webhookSecret = config('services.geniuspay.webhook_secret');
        if ($webhookSecret) {
            $signature = $request->header('X-Webhook-Signature') ?? $request->header('X-Signature');
            $expected  = hash_hmac('sha256', $request->getContent(), $webhookSecret);
            if (!hash_equals($expected, $signature ?? '')) {
                Log::warning('GeniusPay webhook signature invalide');
                return response()->json(['error' => 'Signature invalide'], 401);
            }
        }

        $payload = $request->all();
        $event   = $payload['event'] ?? null;
        $data    = $payload['data'] ?? [];

        Log::info('GeniusPay webhook reçu', ['event' => $event, 'data' => $data]);

        // Paiement réussi
        if (in_array($event, ['payment.success', 'payment.completed'])) {
            $metadata      = $data['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;
            $reference     = $metadata['reference'] ?? $data['reference'] ?? null;

            $transaction = $transactionId
                ? Transaction::find($transactionId)
                : Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->statut !== 'reussi') {
                $transaction->update([
                    'statut'           => 'reussi',
                    'reference_paiement' => $data['reference'] ?? $transaction->reference_paiement,
                    'moyen_paiement'   => $data['gateway'] ?? 'geniuspay',
                ]);

                // Créer les achats et envoyer les fichiers
                $this->livrerProduits($transaction);
            }
        }

        // Paiement échoué
        if (in_array($event, ['payment.failed', 'payment.cancelled'])) {
            $metadata      = $data['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;

            if ($transactionId) {
                Transaction::where('id', $transactionId)->update(['statut' => 'echoue']);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Callback GeniusPay (redirection après paiement)
     */
    public function callback(Request $request)
    {
        $status    = $request->get('status');
        $reference = $request->get('reference');

        if ($status === 'completed' || $status === 'success') {

            // Trouver la transaction via la référence GeniusPay ou la session
            $transactionId = Session::get('checkout_transaction_' . $this->boutique->id);
            $transaction   = $transactionId ? Transaction::find($transactionId) : null;

            // Si pas trouvée via session, chercher par référence GeniusPay
            if (!$transaction && $reference) {
                $transaction = Transaction::where('reference_paiement', $reference)
                    ->orWhere('reference', $reference)
                    ->first();
            }

            if ($transaction && $transaction->statut !== 'reussi') {
                // Mettre à jour le statut
                $transaction->update([
                    'statut'             => 'reussi',
                    'reference_paiement' => $reference ?? $transaction->reference_paiement,
                    'moyen_paiement'     => 'geniuspay',
                ]);

                // Créer les achats et livrer les produits
                $this->livrerProduits($transaction);

                // Envoyer email de confirmation avec liens + notification au marchand
                try {
                    $emailService = new \App\Services\Email\EmailService();
                    $transaction->load(['achats.produit', 'client', 'boutique.utilisateur', 'boutique.configuration']);
                    $emailService->envoyerFichiersAchat($transaction);
                    $emailService->envoyerNotificationVente($transaction);
                    \App\Services\NotificationService::nouvelleVente($transaction);
                    Log::info('Email confirmation + notification marchand envoyés pour transaction #' . $transaction->id);
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email confirmation', ['error' => $e->getMessage()]);
                }

                // Connecter automatiquement le client
                if ($transaction->client) {
                    Session::put('client_acces_' . $this->boutique->id, $transaction->client->email);
                }
            }

            return redirect()->route('boutique.checkout.succes');
        }

        return redirect()->route('boutique.checkout.annulation');
    }

    /**
     * Livrer les produits après paiement confirmé
     */
    protected function livrerProduits(Transaction $transaction)
    {
        try {
            $details = $transaction->details ?? [];

            foreach ($details as $item) {
                $produit = Produit::find($item['produit_id'] ?? null);
                if (!$produit) continue;

                // Créer l'achat
                \App\Models\Achat::firstOrCreate([
                    'transaction_id' => $transaction->id,
                    'produit_id'     => $produit->id,
                    'client_id'      => $transaction->client_id,
                ], [
                    'boutique_id' => $transaction->boutique_id,
                    'montant'     => $produit->prix,
                    'token_telechargement' => Str::random(40),
                ]);
            }

            Log::info('Produits livrés pour transaction #' . $transaction->id);
        } catch (\Exception $e) {
            Log::error('Erreur livraison produits', ['error' => $e->getMessage()]);
        }
    }
}