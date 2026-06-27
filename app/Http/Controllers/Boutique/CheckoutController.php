<?php

namespace App\Http\Controllers\Boutique;

use App\Jobs\SendPurchaseFilesEmailJob;
use App\Jobs\SendSaleNotificationEmailJob;
use App\Models\Produit;
use App\Models\Transaction;
use App\Models\Client;
use App\Models\Upsell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Moneroo\Laravel\Payment as MonerooPayment;
use Moneroo\Laravel\Exceptions\UnauthorizedException;
use Moneroo\Laravel\Exceptions\InvalidPayloadException;

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
     * Initie le paiement via Moneroo (PawaPay) → redirige vers checkout Moneroo
     */
    public function initierPaiement(Request $request)
    {
        $request->validate([
            'nom'   => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $panier = Session::get('panier_' . $this->boutique->id, []);

        if (empty($panier)) {
            return redirect()->route('boutique.panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        $produits = Produit::whereIn('id', array_keys($panier))->get();
        $total    = $produits->sum('prix');

        // Montant minimum requis par les opérateurs mobile money (Wave CI, Orange Money, etc.)
        $montantMinimum = 500;
        if ($total < $montantMinimum) {
            return back()->with('error',
                'Le montant minimum pour un paiement mobile est de ' . number_format($montantMinimum, 0, ',', ' ') . ' FCFA. ' .
                'Votre panier est à ' . number_format($total, 0, ',', ' ') . ' FCFA.'
            );
        }

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

        // Appel API Moneroo via SDK officiel
        try {
            $nomParts  = explode(' ', trim($request->nom), 2);
            $firstName = $nomParts[0];
            $lastName  = $nomParts[1] ?? $nomParts[0];

            // Formater le numéro de téléphone en E.164
            $telephone = preg_replace('/[\s\-\(\)\.]+/', '', $request->telephone); // supprimer espaces/tirets
            if ($telephone && !str_starts_with($telephone, '+')) {
                // Si le numéro commence par 00 → remplacer par +
                if (str_starts_with($telephone, '00')) {
                    $telephone = '+' . substr($telephone, 2);
                }
                // Si le numéro commence par un 0 local (ex: 0788101432 pour CI)
                // → ajouter l'indicatif du pays détecté ou 225 par défaut (CI)
                elseif (str_starts_with($telephone, '0')) {
                    $telephone = '+225' . $telephone; // garde le 0 : +2250788101432 format CI valide
                }
                // Sinon : ajouter + directement (ex: 2250788101432)
                else {
                    $telephone = '+' . $telephone;
                }
            }

            // NE PAS envoyer payment_method à Moneroo :
            // Si on pré-sélectionne "wave", FedaPay essaie d'initier un paiement
            // directement via le numéro de téléphone (flow API) et échoue si le solde
            // est insuffisant ou le compte non trouvé, SANS jamais afficher le QR code.
            // En laissant Moneroo choisir, il affiche sa page avec QR Wave → l'utilisateur scanne.
            // NE PAS envoyer le téléphone dans customer :
            // Si FedaPay reçoit le numéro de téléphone, il essaie un paiement PUSH/USSD
            // directement sur Wave (notification téléphone) → échoue souvent.
            // Sans téléphone → FedaPay affiche le QR code Wave à scanner sur desktop.
            $initData = [
                'amount'      => (int) $total,
                'currency'    => 'XOF',
                'description' => 'Commande ' . $reference . ' — ' . $this->boutique->nom,
                'customer'    => [
                    'email'      => $request->email,
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    // Pas de 'phone' → force le mode QR code sur FedaPay/Wave CI
                ],
                'metadata'    => [
                    'reference'      => $reference,
                    'transaction_id' => $transaction->id,
                    'boutique_id'    => $this->boutique->id,
                ],
                'return_url'  => route('boutique.checkout.succes'),
                'cancel_url'  => route('boutique.checkout.annulation'),
            ];

            Log::info('Moneroo init — données envoyées', [
                'amount'         => $initData['amount'],
                'phone'          => $initData['customer']['phone'] ?? null,
                'email'          => $initData['customer']['email'],
                'payment_method' => $initData['payment_method'] ?? null,
                'return_url'     => $initData['return_url'],
            ]);

            $moneroo = new MonerooPayment();
            $payment = $moneroo->init($initData);

            Log::info('Moneroo init — réponse', [
                'payment_id'   => $payment->id ?? null,
                'checkout_url' => $payment->checkout_url ?? null,
                'status'       => $payment->status ?? null,
            ]);

            // Sauvegarder la référence Moneroo et rediriger
            $transaction->update(['reference_paiement' => $payment->id ?? null]);

            return redirect()->away($payment->checkout_url);

        } catch (UnauthorizedException $e) {
            Log::error('Moneroo clé API invalide', ['message' => $e->getMessage()]);
            return back()->with('error', 'Erreur de configuration du paiement. Contactez le support.');
        } catch (InvalidPayloadException $e) {
            Log::error('Moneroo données invalides', ['message' => $e->getMessage()]);
            return back()->with('error', 'Données de paiement invalides : ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Moneroo exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'Erreur de connexion au service de paiement.');
        }
    }

    /**
     * Page de succès — le client revient après paiement réussi
     * Moneroo redirige avec ?paymentId=py_xxx&paymentStatus=pending|success
     */
    public function succes(Request $request)
    {
        $transactionId = Session::get('checkout_transaction_' . $this->boutique->id);
        $transaction   = $transactionId ? Transaction::find($transactionId) : null;

        // Moneroo renvoie ?paymentId=py_xxx (camelCase) ou ?id=py_xxx
        $monerooId = $request->get('paymentId')        // format Moneroo actuel
                  ?? $request->get('id')               // ancien format
                  ?? $request->get('payment_id')
                  ?? $request->get('transaction_id');

        // Fallback 1 : chercher par ID Moneroo
        if (!$transaction && $monerooId) {
            $transaction = Transaction::where('reference_paiement', $monerooId)
                ->where('boutique_id', $this->boutique->id)
                ->first();
        }

        // Fallback 2 : chercher via notre référence interne
        if (!$transaction) {
            $reference = $request->get('reference');
            if ($reference) {
                $transaction = Transaction::where('reference', $reference)
                    ->orWhere('reference_paiement', $reference)
                    ->where('boutique_id', $this->boutique->id)
                    ->first();
            }
        }

        // Confirmer le paiement si encore en attente
        if ($transaction && $transaction->statut !== 'reussi') {

            // ID Moneroo : depuis la transaction ou la query string
            $monerooPaymentId = $transaction->reference_paiement ?? $monerooId;

            // Vérifier le statut directement via l'API Moneroo
            $paiementConfirme = false;
            $statusApi        = null;

            if ($monerooPaymentId) {
                try {
                    $moneroo     = new MonerooPayment();
                    $paymentData = $moneroo->verify($monerooPaymentId);
                    $statusApi   = $paymentData->status ?? null;
                    $paiementConfirme = in_array($statusApi, ['success', 'completed', 'paid']);

                    Log::info('Moneroo verify succes()', [
                        'payment_id' => $monerooPaymentId,
                        'status'     => $statusApi,
                        'confirmed'  => $paiementConfirme,
                    ]);
                } catch (\Exception $e) {
                    // API Moneroo inaccessible : on fait confiance au redirect seulement si statut != pending
                    $statusUrl = $request->get('paymentStatus') ?? $request->get('status');
                    $paiementConfirme = in_array($statusUrl, ['success', 'completed', 'paid']);
                    Log::warning('Moneroo verify() indisponible', [
                        'payment_id'  => $monerooPaymentId,
                        'status_url'  => $statusUrl,
                        'confirmed'   => $paiementConfirme,
                        'error'       => $e->getMessage(),
                    ]);
                }
            }

            if ($paiementConfirme) {
                $updated = DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->where('statut', '!=', 'reussi')
                    ->update([
                        'statut'             => 'reussi',
                        'reference_paiement' => $monerooPaymentId ?? $transaction->reference_paiement,
                        'moyen_paiement'     => 'moneroo',
                        'updated_at'         => now(),
                    ]);

                if ($updated) {
                    $transaction->refresh();
                    $this->livrerProduits($transaction);
                    dispatch(new SendPurchaseFilesEmailJob($transaction->id));
                    dispatch(new SendSaleNotificationEmailJob($transaction->id));
                    try {
                        \App\Services\NotificationService::nouvelleVente($transaction);
                    } catch (\Exception $e) {
                        Log::warning('Notification in-app échouée', ['error' => $e->getMessage()]);
                    }
                }
            }

            // Paiement échoué / annulé → rediriger vers la page d'annulation
            if (!$paiementConfirme && in_array($statusApi, ['failed', 'cancelled', 'expired'])) {
                $transaction->update(['statut' => 'echoue']);
                Session::forget('checkout_transaction_' . $this->boutique->id);
                Log::info('Paiement échoué', ['payment_id' => $monerooPaymentId, 'status' => $statusApi]);
                return redirect()->route('boutique.checkout.annulation')
                    ->with('error', 'Le paiement a échoué ou a été annulé. Veuillez réessayer.');
            }

            // Paiement encore en attente (ex: Wave CI sur PC → l'utilisateur doit confirmer dans Wave)
            if (!$paiementConfirme && in_array($statusApi, ['pending', 'initiated', null])) {
                if ($monerooPaymentId) {
                    Session::put('polling_payment_id_' . $this->boutique->id, $monerooPaymentId);
                    if (!$transaction->reference_paiement) {
                        $transaction->update(['reference_paiement' => $monerooPaymentId]);
                    }
                }
                return view('boutique.checkout.en_attente', [
                    'boutique'         => $this->boutique,
                    'transaction'      => $transaction,
                    'monerooPaymentId' => $monerooPaymentId,
                ]);
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
     * Endpoint AJAX de polling — vérifie le statut du paiement en cours
     * Appelé toutes les 5s depuis la page "en_attente"
     */
    public function verifierStatut(Request $request)
    {
        $paymentId = $request->get('payment_id')
                  ?? Session::get('polling_payment_id_' . $this->boutique->id);

        if (!$paymentId) {
            return response()->json(['status' => 'unknown']);
        }

        // Chercher la transaction
        $transaction = Transaction::where('reference_paiement', $paymentId)
            ->where('boutique_id', $this->boutique->id)
            ->first();

        // Déjà confirmée en base
        if ($transaction && $transaction->statut === 'reussi') {
            Session::forget('polling_payment_id_' . $this->boutique->id);
            return response()->json(['status' => 'success']);
        }

        // Vérifier via l'API Moneroo
        try {
            $moneroo     = new MonerooPayment();
            $paymentData = $moneroo->verify($paymentId);
            $statusApi   = $paymentData->status ?? 'unknown';

            Log::info('Polling paiement', ['payment_id' => $paymentId, 'status' => $statusApi]);

            if (in_array($statusApi, ['success', 'completed', 'paid'])) {
                // Confirmer la transaction
                if ($transaction && $transaction->statut !== 'reussi') {
                    $updated = DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->where('statut', '!=', 'reussi')
                        ->update([
                            'statut'         => 'reussi',
                            'moyen_paiement' => 'moneroo',
                            'updated_at'     => now(),
                        ]);

                    if ($updated) {
                        $transaction->refresh();
                        $this->livrerProduits($transaction);
                        dispatch(new SendPurchaseFilesEmailJob($transaction->id));
                        dispatch(new SendSaleNotificationEmailJob($transaction->id));
                        try {
                            \App\Services\NotificationService::nouvelleVente($transaction);
                        } catch (\Exception $e) {}
                    }
                }
                Session::forget('polling_payment_id_' . $this->boutique->id);
                return response()->json(['status' => 'success']);
            }

            if (in_array($statusApi, ['failed', 'cancelled', 'expired'])) {
                if ($transaction) {
                    $transaction->update(['statut' => 'echoue']);
                }
                Session::forget('polling_payment_id_' . $this->boutique->id);
                return response()->json(['status' => 'failed']);
            }

            return response()->json(['status' => 'pending']);

        } catch (\Exception $e) {
            Log::warning('Polling verify() erreur', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'pending']);
        }
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
     * Webhook Moneroo — appelé par Moneroo après paiement
     */
    public function webhook(Request $request)
    {
        // Vérifier la signature webhook (sécurité)
        $webhookSecret = config('services.moneroo.webhook_secret');
        if ($webhookSecret) {
            $signature = $request->header('X-Moneroo-Signature')
                      ?? $request->header('X-Webhook-Signature')
                      ?? $request->header('X-Signature');
            $expected  = hash_hmac('sha256', $request->getContent(), $webhookSecret);
            if (!hash_equals($expected, $signature ?? '')) {
                Log::warning('Moneroo webhook signature invalide');
                return response()->json(['error' => 'Signature invalide'], 401);
            }
        }

        $payload = $request->all();
        $event   = $payload['event'] ?? $payload['type'] ?? null;
        $data    = $payload['data'] ?? $payload;

        Log::info('Moneroo webhook reçu', ['event' => $event, 'data' => $data]);

        // Paiement réussi
        if (in_array($event, ['payment.success', 'payment.completed', 'payment.paid'])) {
            $metadata      = $data['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;
            $reference     = $metadata['reference'] ?? $data['reference'] ?? null;

            $transaction = $transactionId
                ? Transaction::find($transactionId)
                : Transaction::where('reference', $reference)->first();

            if ($transaction && $transaction->statut !== 'reussi') {
                $updated = DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->where('statut', '!=', 'reussi')
                    ->update([
                        'statut'             => 'reussi',
                        'reference_paiement' => $data['id'] ?? $data['reference'] ?? $transaction->reference_paiement,
                        'moyen_paiement'     => $data['method'] ?? $data['payment_method'] ?? $data['gateway'] ?? 'moneroo',
                        'details_paiement'   => json_encode($data),
                        'updated_at'         => now(),
                    ]);

                if ($updated) {
                    $transaction->refresh();
                    $this->livrerProduits($transaction);

                    dispatch(new SendPurchaseFilesEmailJob($transaction->id));
                    dispatch(new SendSaleNotificationEmailJob($transaction->id));

                    try {
                        \App\Services\NotificationService::nouvelleVente($transaction);
                    } catch (\Exception $e) {
                        Log::warning('Notification in-app échouée (webhook)', ['error' => $e->getMessage()]);
                    }
                }
            }
        }

        // Paiement échoué / annulé
        if (in_array($event, ['payment.failed', 'payment.cancelled', 'payment.expired'])) {
            $metadata      = $data['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;

            if ($transactionId) {
                Transaction::where('id', $transactionId)->update(['statut' => 'echoue']);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Callback Moneroo (redirection après paiement)
     */
    public function callback(Request $request)
    {
        $status    = $request->get('status');
        // Moneroo peut envoyer ?id=py_xxx ou ?payment_id=xxx ou ?reference=xxx
        $monerooId = $request->get('id') ?? $request->get('payment_id');
        $reference = $request->get('reference');

        if (in_array($status, ['completed', 'success', 'paid'])) {

            // Trouver la transaction
            $transactionId = Session::get('checkout_transaction_' . $this->boutique->id);
            $transaction   = $transactionId ? Transaction::find($transactionId) : null;

            if (!$transaction && $monerooId) {
                $transaction = Transaction::where('reference_paiement', $monerooId)
                    ->where('boutique_id', $this->boutique->id)
                    ->first();
            }

            if (!$transaction && $reference) {
                $transaction = Transaction::where('reference_paiement', $reference)
                    ->orWhere('reference', $reference)
                    ->first();
            }

            if ($transaction && $transaction->statut !== 'reussi') {
                $updated = DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->where('statut', '!=', 'reussi')
                    ->update([
                        'statut'             => 'reussi',
                        'reference_paiement' => $monerooId ?? $reference ?? $transaction->reference_paiement,
                        'moyen_paiement'     => 'moneroo',
                        'updated_at'         => now(),
                    ]);

                if ($updated) {
                    $transaction->refresh();
                    $this->livrerProduits($transaction);

                    dispatch(new SendPurchaseFilesEmailJob($transaction->id));
                    dispatch(new SendSaleNotificationEmailJob($transaction->id));

                    Log::info('Jobs emails dispatchés pour transaction #' . $transaction->id);

                    try {
                        \App\Services\NotificationService::nouvelleVente($transaction);
                    } catch (\Exception $e) {
                        Log::warning('Notification in-app échouée', ['error' => $e->getMessage()]);
                    }
                }

                if ($transaction->client) {
                    Session::put('client_acces_' . $this->boutique->id, $transaction->client->email);
                }
            }

            return redirect()->route('boutique.checkout.succes');
        }

        return redirect()->route('boutique.checkout.annulation');
    }

    /**
     * Livrer les produits après paiement confirmé (public pour la sync admin)
     */
    public function livrerProduitsPublic(Transaction $transaction)
    {
        $this->livrerProduits($transaction);
    }

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