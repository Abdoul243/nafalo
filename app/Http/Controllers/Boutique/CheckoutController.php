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

    private function geniuspayHeaders(): array
    {
        return [
            'X-API-Key'    => config('services.geniuspay.public_key'),
            'X-API-Secret' => config('services.geniuspay.secret_key'),
            'Content-Type' => 'application/json',
        ];
    }

    private function geniuspayUrl(string $path = ''): string
    {
        return rtrim(config('services.geniuspay.api_url', 'http://pay.genius.ci/api/v1/merchant'), '/') . $path;
    }

    public function informations()
    {
        $panier = Session::get('panier_' . $this->boutique->id, []);

        if (empty($panier)) {
            return redirect()->route('boutique.panier.index')
                ->with('error', 'Votre panier est vide.');
        }

        $produits = Produit::whereIn('id', array_keys($panier))->get();
        $total    = $produits->sum('prix');

        return view('boutique.checkout.informations', [
            'boutique' => $this->boutique,
            'produits' => $produits,
            'panier'   => $panier,
            'total'    => $total,
        ]);
    }

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

        if ($total < 200) {
            return back()->with('error',
                'Le montant minimum pour un paiement est de 200 FCFA. ' .
                'Votre panier est à ' . number_format($total, 0, ',', ' ') . ' FCFA.'
            );
        }

        $client = Client::firstOrCreate(
            ['email' => $request->email, 'boutique_id' => $this->boutique->id],
            ['nom' => $request->nom, 'telephone' => $request->telephone]
        );

        $reference       = 'DS-' . strtoupper(Str::random(8));
        $commission      = round($total * 0.05, 2);
        $montantMarchand = round($total - $commission, 2);

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

        Session::put('checkout_client_' . $this->boutique->id, [
            'nom'       => $request->nom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
        ]);
        Session::put('checkout_transaction_' . $this->boutique->id, $transaction->id);

        try {
            $payload = [
                'amount'      => (int) $total,
                'currency'    => 'XOF',
                'description' => 'Commande ' . $reference . ' — ' . $this->boutique->nom,
                'customer'    => [
                    'name'  => $request->nom,
                    'email' => $request->email,
                ],
                'success_url' => route('boutique.checkout.succes'),
                'error_url'   => route('boutique.checkout.annulation'),
                'metadata'    => [
                    'reference'      => $reference,
                    'transaction_id' => $transaction->id,
                    'boutique_id'    => $this->boutique->id,
                ],
            ];

            if ($request->telephone) {
                $payload['customer']['phone'] = $request->telephone;
            }

            Log::info('GeniusPay init — données envoyées', $payload);

            $response = Http::timeout(20)
                ->withHeaders($this->geniuspayHeaders())
                ->post($this->geniuspayUrl('/payments'), $payload);

            Log::info('GeniusPay init — réponse', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            if (!$response->successful()) {
                Log::error('GeniusPay erreur API', ['body' => $response->body()]);
                return back()->with('error', 'Erreur de connexion au service de paiement. Veuillez réessayer.');
            }

            $data        = $response->json('data');
            $checkoutUrl = $data['checkout_url'] ?? $data['payment_url'] ?? null;
            $gpReference = $data['reference'] ?? $data['id'] ?? null;

            if (!$checkoutUrl) {
                Log::error('GeniusPay: pas de checkout_url', ['data' => $data]);
                return back()->with('error', 'Impossible d\'initialiser le paiement. Veuillez réessayer.');
            }

            $transaction->update(['reference_paiement' => $gpReference]);

            return redirect()->away($checkoutUrl);

        } catch (\Exception $e) {
            Log::error('GeniusPay exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'Erreur de connexion au service de paiement.');
        }
    }

    public function succes(Request $request)
    {
        $transactionId = Session::get('checkout_transaction_' . $this->boutique->id);
        $transaction   = $transactionId ? Transaction::find($transactionId) : null;

        // GeniusPay peut renvoyer ?reference=MTX-xxx ou ?transaction_id=xxx
        $gpReference = $request->get('reference')
                    ?? $request->get('transaction_id')
                    ?? $request->get('id');

        if (!$transaction && $gpReference) {
            $transaction = Transaction::where('reference_paiement', $gpReference)
                ->where('boutique_id', $this->boutique->id)
                ->first();
        }

        if ($transaction && $transaction->statut !== 'reussi') {

            $gpRef     = $transaction->reference_paiement ?? $gpReference;
            $statusUrl = $request->get('status');

            // ── SÉCURITÉ ──────────────────────────────────────────────
            // Le paramètre `status` de l'URL est contrôlable par le client.
            // On ne s'y fie JAMAIS pour confirmer un paiement : la source de
            // vérité est UNIQUEMENT l'API GeniusPay (et le webhook signé).
            $statusApi        = null;
            $paiementConfirme = false;

            if ($gpRef) {
                try {
                    $response  = Http::timeout(15)
                        ->withHeaders($this->geniuspayHeaders())
                        ->get($this->geniuspayUrl('/payments/' . $gpRef));

                    $statusApi        = $response->json('data.status');
                    $paiementConfirme = in_array($statusApi, ['completed', 'success', 'paid']);

                    Log::info('GeniusPay verify succes()', [
                        'reference' => $gpRef,
                        'status'    => $statusApi,
                        'confirmed' => $paiementConfirme,
                    ]);
                } catch (\Exception $e) {
                    Log::warning('GeniusPay verify() indisponible', ['error' => $e->getMessage()]);
                }
            }

            // Tolérance UNIQUEMENT en local : en sandbox l'API renvoie souvent
            // "pending" juste après la redirection → on accepte le statut URL
            // pour pouvoir tester. JAMAIS en production.
            if (!$paiementConfirme
                && app()->environment('local')
                && in_array($statusUrl, ['completed', 'success', 'paid'])) {
                $paiementConfirme = true;
                Log::warning('Paiement confirmé via statut URL (LOCAL uniquement)', ['reference' => $gpRef]);
            }

            if ($paiementConfirme) {
                $updated = DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->where('statut', '!=', 'reussi')
                    ->update([
                        'statut'             => 'reussi',
                        'reference_paiement' => $gpRef ?? $transaction->reference_paiement,
                        'moyen_paiement'     => 'geniuspay',
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

            if (!$paiementConfirme && in_array($statusApi, ['failed', 'cancelled'])) {
                $transaction->update(['statut' => 'echoue']);
                Session::forget('checkout_transaction_' . $this->boutique->id);
                return redirect()->route('boutique.checkout.annulation')
                    ->with('error', 'Le paiement a échoué ou a été annulé. Veuillez réessayer.');
            }

            if (!$paiementConfirme && in_array($statusApi, ['pending', 'processing', null])) {
                if ($gpRef) {
                    Session::put('polling_payment_id_' . $this->boutique->id, $gpRef);
                    if (!$transaction->reference_paiement) {
                        $transaction->update(['reference_paiement' => $gpRef]);
                    }
                }
                return view('boutique.checkout.en_attente', [
                    'boutique'         => $this->boutique,
                    'transaction'      => $transaction,
                    'paymentReference' => $gpRef,
                ]);
            }
        }

        $achats = collect();
        if ($transaction) {
            $achats = \App\Models\Achat::where('transaction_id', $transaction->id)
                ->with('produit')
                ->get();

            if ($transaction->client) {
                Session::put('client_acces_' . $this->boutique->id, $transaction->client->email);
            }
        }

        Session::forget('panier_' . $this->boutique->id);
        Session::forget('checkout_transaction_' . $this->boutique->id);

        $upsells = collect();
        if ($achats->isNotEmpty()) {
            $produitIds = $achats->pluck('produit_id')->filter()->unique();
            $upsells = Upsell::whereIn('produit_id', $produitIds)
                ->where('est_actif', true)
                ->with('produitUpsell')
                ->orderBy('ordre')
                ->get()
                ->reject(fn($u) => $produitIds->contains($u->produit_upsell_id));
        }

        return view('boutique.checkout.succes', [
            'boutique'    => $this->boutique,
            'transaction' => $transaction,
            'achats'      => $achats,
            'upsells'     => $upsells,
        ]);
    }

    public function verifierStatut(Request $request)
    {
        $gpRef = $request->get('payment_id')
              ?? Session::get('polling_payment_id_' . $this->boutique->id);

        if (!$gpRef) {
            return response()->json(['status' => 'unknown']);
        }

        $transaction = Transaction::where('reference_paiement', $gpRef)
            ->where('boutique_id', $this->boutique->id)
            ->first();

        if ($transaction && $transaction->statut === 'reussi') {
            Session::forget('polling_payment_id_' . $this->boutique->id);
            return response()->json(['status' => 'success']);
        }

        try {
            $response  = Http::timeout(15)
                ->withHeaders($this->geniuspayHeaders())
                ->get($this->geniuspayUrl('/payments/' . $gpRef));

            $statusApi = $response->json('data.status') ?? 'unknown';

            Log::info('Polling GeniusPay', ['reference' => $gpRef, 'status' => $statusApi]);

            if (in_array($statusApi, ['completed', 'success', 'paid'])) {
                if ($transaction && $transaction->statut !== 'reussi') {
                    $updated = DB::table('transactions')
                        ->where('id', $transaction->id)
                        ->where('statut', '!=', 'reussi')
                        ->update([
                            'statut'         => 'reussi',
                            'moyen_paiement' => 'geniuspay',
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

            if (in_array($statusApi, ['failed', 'cancelled'])) {
                if ($transaction) {
                    $transaction->update(['statut' => 'echoue']);
                }
                Session::forget('polling_payment_id_' . $this->boutique->id);
                return response()->json(['status' => 'failed']);
            }

            return response()->json(['status' => 'pending']);

        } catch (\Exception $e) {
            Log::warning('Polling GeniusPay erreur', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'pending']);
        }
    }

    public function annulation()
    {
        return view('boutique.checkout.annulation', [
            'boutique' => $this->boutique,
        ]);
    }

    public function webhook(Request $request)
    {
        $webhookSecret = config('services.geniuspay.webhook_secret');

        if ($webhookSecret) {
            // Secret configuré → la signature est OBLIGATOIRE et doit être valide.
            $signature = $request->header('X-GeniusPay-Signature');
            $expected  = hash_hmac('sha256', $request->getContent(), $webhookSecret);
            if (!$signature || !hash_equals($expected, $signature)) {
                Log::warning('GeniusPay webhook signature invalide/absente', [
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Signature invalide'], 401);
            }
        } elseif (!app()->environment('local')) {
            // Hors développement, on REFUSE tout webhook tant que le secret
            // n'est pas configuré (fail-closed) : sinon n'importe qui pourrait
            // simuler un paiement réussi.
            Log::error('GeniusPay webhook reçu sans secret configuré — rejeté. '
                . 'Définissez GENIUSPAY_WEBHOOK_SECRET dans .env.');
            return response()->json(['error' => 'Webhook non configuré'], 503);
        }

        $event       = $request->input('event');
        $transaction = $request->input('data.transaction');

        Log::info('GeniusPay webhook reçu', ['event' => $event, 'transaction' => $transaction]);

        if ($event === 'payment.success') {
            $metadata      = $transaction['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;
            $reference     = $metadata['reference'] ?? $transaction['reference'] ?? null;

            $tx = $transactionId
                ? Transaction::find($transactionId)
                : Transaction::where('reference', $reference)->first();

            if ($tx && $tx->statut !== 'reussi') {
                $updated = DB::table('transactions')
                    ->where('id', $tx->id)
                    ->where('statut', '!=', 'reussi')
                    ->update([
                        'statut'             => 'reussi',
                        'reference_paiement' => $transaction['reference'] ?? $tx->reference_paiement,
                        'moyen_paiement'     => $transaction['payment_method'] ?? 'geniuspay',
                        'details_paiement'   => json_encode($transaction),
                        'updated_at'         => now(),
                    ]);

                if ($updated) {
                    $tx->refresh();
                    $this->livrerProduits($tx);
                    dispatch(new SendPurchaseFilesEmailJob($tx->id));
                    dispatch(new SendSaleNotificationEmailJob($tx->id));
                    try {
                        \App\Services\NotificationService::nouvelleVente($tx);
                    } catch (\Exception $e) {
                        Log::warning('Notification in-app échouée (webhook)', ['error' => $e->getMessage()]);
                    }
                }
            }
        }

        if (in_array($event, ['payment.failed', 'payment.cancelled'])) {
            $metadata      = $transaction['metadata'] ?? [];
            $transactionId = $metadata['transaction_id'] ?? null;
            if ($transactionId) {
                Transaction::where('id', $transactionId)->update(['statut' => 'echoue']);
            }
        }

        return response()->json(['success' => true]);
    }

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

                $achat = \App\Models\Achat::firstOrCreate([
                    'transaction_id' => $transaction->id,
                    'produit_id'     => $produit->id,
                    'client_id'      => $transaction->client_id,
                ], [
                    'boutique_id'          => $transaction->boutique_id,
                    'montant'              => $produit->prix,
                    'prix_unitaire'        => $produit->prix,
                    'quantite'             => 1,
                    'token_telechargement' => Str::random(40),
                ]);

                // Abonnement : créer ou prolonger l'accès
                if ($produit->estAbonnement() && $transaction->client_id) {
                    $this->prolongerAbonnement($produit, $transaction);
                }

                // Licence : attribuer une clé disponible au client
                if ($produit->estLicence() && $transaction->client_id) {
                    $this->attribuerCleLicence($produit, $transaction, $achat);
                }

                // Bundle : débloquer chaque produit inclus
                if ($produit->estBundle()) {
                    $this->livrerBundle($produit, $transaction);
                }
            }

            Log::info('Produits livrés pour transaction #' . $transaction->id);
        } catch (\Exception $e) {
            Log::error('Erreur livraison produits', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Crée ou prolonge l'abonnement du client pour un produit récurrent.
     * La nouvelle échéance part de la fin en cours si encore active (cumul),
     * sinon de maintenant.
     */
    protected function prolongerAbonnement(Produit $produit, Transaction $transaction): void
    {
        $intervalle = $produit->abonnement_intervalle ?: 'mensuel';
        $mois       = \App\Models\Abonnement::moisPourIntervalle($intervalle);

        $abonnement = \App\Models\Abonnement::firstOrNew([
            'client_id'  => $transaction->client_id,
            'produit_id' => $produit->id,
        ]);

        // Base : fin actuelle si encore active, sinon maintenant
        $base = ($abonnement->exists && $abonnement->date_fin && $abonnement->date_fin->isFuture())
            ? $abonnement->date_fin
            : now();

        $abonnement->fill([
            'boutique_id'             => $transaction->boutique_id,
            'statut'                  => 'actif',
            'intervalle'              => $intervalle,
            'prix'                    => $produit->prix,
            'date_debut'              => $abonnement->date_debut ?? now(),
            'date_fin'                => $base->copy()->addMonths($mois),
            'rappel_envoye'           => false,
            'derniere_transaction_id' => $transaction->id,
        ])->save();
    }

    /**
     * Attribue une clé de licence disponible au client (une seule fois par achat).
     */
    protected function attribuerCleLicence(Produit $produit, Transaction $transaction, $achat): void
    {
        // Déjà une clé pour cet achat ? on ne réattribue pas.
        $dejaAttribuee = \App\Models\CleLicence::where('achat_id', $achat->id)->exists();
        if ($dejaAttribuee) return;

        $cle = \App\Models\CleLicence::where('produit_id', $produit->id)
            ->where('statut', 'disponible')
            ->orderBy('id')
            ->lockForUpdate()
            ->first();

        if (!$cle) {
            // Stock épuisé — à surveiller dans les logs (le marchand doit ajouter des clés).
            Log::warning('Licence en rupture de stock', [
                'produit_id'     => $produit->id,
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $cle->update([
            'statut'       => 'attribuee',
            'client_id'    => $transaction->client_id,
            'achat_id'     => $achat->id,
            'attribuee_at' => now(),
        ]);
    }

    /**
     * Débloque tous les produits inclus dans un bundle : crée un achat pour
     * chacun et applique sa livraison spécifique (clé de licence, etc.).
     */
    protected function livrerBundle(Produit $produit, Transaction $transaction): void
    {
        foreach ($produit->produitsInclus as $inclus) {
            $achatInclus = \App\Models\Achat::firstOrCreate([
                'transaction_id' => $transaction->id,
                'produit_id'     => $inclus->id,
                'client_id'      => $transaction->client_id,
            ], [
                'boutique_id'          => $transaction->boutique_id,
                'montant'              => 0, // inclus dans le pack
                'prix_unitaire'        => 0,
                'quantite'             => 1,
                'token_telechargement' => Str::random(40),
            ]);

            if ($inclus->estLicence() && $transaction->client_id) {
                $this->attribuerCleLicence($inclus, $transaction, $achatInclus);
            }
            if ($inclus->estAbonnement() && $transaction->client_id) {
                $this->prolongerAbonnement($inclus, $transaction);
            }
        }
    }
}
