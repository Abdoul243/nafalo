<?php

namespace App\Services\Email;

use App\Models\Boutique;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function envoyerConfirmationAchat(Transaction $transaction)
    {
        $boutique = $transaction->boutique;
        $client = $transaction->client;

        $toEmail = $this->normalizeEmail($client->email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) {
            Log::warning('Email confirmation ignored: invalid client email', [
                'client_id' => $client->id,
                'email' => $client->email,
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $template = $boutique->configuration->email_template_achat
            ?? $this->getTemplateParDefaut('achat');

        $contenu = $this->remplacerVariables($template, [
            'nom_client' => $client->nom,
            'reference' => $transaction->reference,
            'montant' => $transaction->montant_total,
            'devise' => $boutique->configuration->devise ?? 'EUR',
            'boutique_nom' => $boutique->nom,
            'lien_achats' => route('client.acces.demande'),
        ]);

        Mail::send([], [], function ($message) use ($client, $boutique, $contenu, $toEmail, $fromEmail) {
            $message->to($toEmail, $client->nom)
                ->from($fromEmail, $boutique->nom)
                ->subject('Confirmation de votre achat - ' . $boutique->nom)
                ->html($contenu);
        });
    }

    public function envoyerCodeAcces(Client $client, string $code)
    {
        $boutique = $client->boutique;

        $toEmail = $this->normalizeEmail($client->email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) {
            Log::warning('Email access code ignored: invalid client email', [
                'client_id' => $client->id,
                'email' => $client->email,
            ]);
            return;
        }

        $contenu = "
            <h1>Votre code d'acces</h1>
            <p>Bonjour {$client->nom},</p>
            <p>Voici votre code d'acces pour telecharger vos achats :</p>
            <h2 style='font-size: 32px; text-align: center; padding: 20px; background: #f0f0f0;'>{$code}</h2>
            <p>Ce code est valable 15 minutes.</p>
            <p>Lien d'acces : " . route('client.acces.verification') . "</p>
        ";

        Mail::send([], [], function ($message) use ($client, $boutique, $contenu, $toEmail, $fromEmail) {
            $message->to($toEmail, $client->nom)
                ->from($fromEmail, $boutique->nom)
                ->subject("Votre code d'acces - " . $boutique->nom)
                ->html($contenu);
        });
    }

    public function envoyerRelancePanier($email, $nom, $panier, $boutique)
    {
        $toEmail   = $this->normalizeEmail($email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) {
            Log::warning('Cart reminder ignored: invalid email', [
                'email'       => $email,
                'boutique_id' => $boutique->id ?? null,
            ]);
            return;
        }

        $lienBoutique = route('boutique.accueil');

        // Construire la liste des produits abandonnés
        $produitsHtml = '';
        if (!empty($panier)) {
            foreach ($panier as $item) {
                $nom_produit  = $item['nom'] ?? 'Produit';
                $prix         = number_format($item['prix'] ?? 0, 0, ',', ' ');
                $produitsHtml .= "
                    <tr>
                        <td style='padding:12px;border-bottom:1px solid #e5e7eb;color:#0f172a;font-weight:500;'>{$nom_produit}</td>
                        <td style='padding:12px;border-bottom:1px solid #e5e7eb;text-align:right;color:#2563eb;font-weight:700;'>{$prix} FCFA</td>
                    </tr>
                ";
            }
        }

        $tableauProduits = '';
        if ($produitsHtml) {
            $tableauProduits = "
                <table width='100%' cellpadding='0' cellspacing='0' style='border-collapse:collapse;margin:16px 0;border:1px solid #e5e7eb;border-radius:10px;'>
                    <thead>
                        <tr style='background:#f8fafc;'>
                            <th align='left' style='padding:12px;border-bottom:2px solid #e5e7eb;font-size:0.85rem;color:#64748b;'>Produit</th>
                            <th align='right' style='padding:12px;border-bottom:2px solid #e5e7eb;font-size:0.85rem;color:#64748b;'>Prix</th>
                        </tr>
                    </thead>
                    <tbody>{$produitsHtml}</tbody>
                </table>
            ";
        }

        $boutique_nom = $boutique->nom;
        $annee        = date('Y');

        $contenu = "
            <!DOCTYPE html>
            <html>
            <head><meta charset='UTF-8'></head>
            <body style='font-family:Arial,sans-serif;background:#f8fafc;margin:0;padding:0;'>
                <div style='max-width:560px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
                    <div style='background:#2563eb;padding:32px;text-align:center;'>
                        <h1 style='color:white;margin:0;font-size:1.4rem;font-weight:800;'>🛒 Votre panier vous attend !</h1>
                    </div>
                    <div style='padding:32px;'>
                        <p style='color:#0f172a;font-size:1rem;'>Bonjour <strong>{$nom}</strong>,</p>
                        <p style='color:#64748b;line-height:1.7;'>Vous avez laissé des articles dans votre panier sur <strong>{$boutique_nom}</strong>. Ils sont toujours disponibles !</p>
                        {$tableauProduits}
                        <div style='text-align:center;margin:28px 0;'>
                            <a href='{$lienBoutique}' style='display:inline-block;background:#2563eb;color:white;padding:14px 32px;border-radius:12px;text-decoration:none;font-weight:700;font-size:1rem;'>
                                ✅ Reprendre ma commande
                            </a>
                        </div>
                        <p style='color:#94a3b8;font-size:0.8rem;text-align:center;'>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
                    </div>
                    <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #e2e8f0;'>
                        <p style='color:#94a3b8;font-size:0.75rem;margin:0;'>{$boutique_nom} &copy; {$annee}</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        Mail::send([], [], function ($message) use ($nom, $boutique, $contenu, $toEmail, $fromEmail) {
            $message->to($toEmail, $nom)
                ->from($fromEmail, $boutique->nom)
                ->subject('🛒 Votre panier vous attend - ' . $boutique->nom)
                ->html($contenu);
        });
    }

    /**
     * Rappel d'échéance d'abonnement (renouvellement manuel).
     */
    public function envoyerRappelAbonnement(\App\Models\Abonnement $abonnement): void
    {
        $client   = $abonnement->client;
        $produit  = $abonnement->produit;
        $boutique = $abonnement->boutique;

        if (!$client || !$produit || !$boutique) return;

        $toEmail   = $this->normalizeEmail($client->email);
        $fromEmail = $this->resolveFromEmail($boutique);
        if (!$toEmail) return;

        $lienRenouveler = route('boutique.checkout.produit', ['id' => $produit->id]);
        $dateFin   = optional($abonnement->date_fin)->format('d/m/Y');
        $jours     = max(0, $abonnement->joursRestants());
        $expire    = !$abonnement->estActif();
        $prix      = number_format($abonnement->prix, 0, ',', ' ');
        $nom       = $client->nom ?? 'Client';
        $annee     = date('Y');

        $titre = $expire
            ? "⏰ Votre abonnement a expiré"
            : "🔔 Votre abonnement expire dans {$jours} jour(s)";

        $message = $expire
            ? "Votre accès à <strong>{$produit->nom}</strong> a pris fin le {$dateFin}. Renouvelez pour continuer."
            : "Votre accès à <strong>{$produit->nom}</strong> se termine le <strong>{$dateFin}</strong>. Renouvelez pour ne pas perdre l'accès.";

        $contenu = "
            <!DOCTYPE html><html><head><meta charset='UTF-8'></head>
            <body style='font-family:Arial,sans-serif;background:#f8fafc;margin:0;padding:0;'>
                <div style='max-width:560px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
                    <div style='background:#4f46e5;padding:32px;text-align:center;'>
                        <h1 style='color:white;margin:0;font-size:1.35rem;font-weight:800;'>{$titre}</h1>
                    </div>
                    <div style='padding:32px;'>
                        <p style='color:#0f172a;'>Bonjour <strong>{$nom}</strong>,</p>
                        <p style='color:#64748b;line-height:1.7;'>{$message}</p>
                        <div style='text-align:center;margin:28px 0;'>
                            <a href='{$lienRenouveler}' style='display:inline-block;background:#4f46e5;color:white;padding:14px 32px;border-radius:12px;text-decoration:none;font-weight:700;'>
                                🔄 Renouveler ({$prix} FCFA)
                            </a>
                        </div>
                    </div>
                    <div style='background:#f8fafc;padding:16px;text-align:center;border-top:1px solid #e2e8f0;'>
                        <p style='color:#94a3b8;font-size:0.75rem;margin:0;'>{$boutique->nom} &copy; {$annee}</p>
                    </div>
                </div>
            </body></html>
        ";

        Mail::send([], [], function ($m) use ($nom, $boutique, $contenu, $toEmail, $fromEmail, $titre) {
            $m->to($toEmail, $nom)
              ->from($fromEmail, $boutique->nom)
              ->subject($titre . ' - ' . $boutique->nom)
              ->html($contenu);
        });
    }

    public function envoyerFichiersAchat(Transaction $transaction): void
    {
        $transaction->loadMissing(['boutique.configuration', 'client', 'achats.produit']);

        $boutique = $transaction->boutique;
        $client = $transaction->client;
        $toEmail = $this->normalizeEmail($client->email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) {
            Log::warning('Purchase files email ignored: invalid client email', [
                'client_id' => $client->id,
                'email' => $client->email,
                'transaction_id' => $transaction->id,
            ]);
            return;
        }

        $itemsHtml = '';
        foreach ($transaction->achats as $achat) {
            $produit = $achat->produit;
            if (!$produit) {
                continue;
            }

            $downloadUrl = route('client.telechargement', ['achat' => $achat->id]);
            $itemsHtml .= "
                <tr>
                    <td style='padding:12px;border-bottom:1px solid #e5e7eb;'>{$produit->nom}</td>
                    <td style='padding:12px;border-bottom:1px solid #e5e7eb;text-align:right;'>
                        <a href='{$downloadUrl}' style='background:#2563eb;color:#fff;padding:8px 14px;border-radius:6px;text-decoration:none;display:inline-block;'>
                            Télécharger
                        </a>
                    </td>
                </tr>
            ";
        }

        $lienAchats = route('client.acces.demande');
        $devise = $boutique->configuration->devise ?? 'EUR';
        $contenu = "
            <h1>Paiement confirmé</h1>
            <p>Bonjour {$client->nom},</p>
            <p>Merci pour votre achat sur <strong>{$boutique->nom}</strong>.</p>
            <p>Référence: <strong>{$transaction->reference}</strong><br>Montant: <strong>{$transaction->montant_total} {$devise}</strong></p>
            <table width='100%' cellpadding='0' cellspacing='0' style='border-collapse:collapse;margin:16px 0;'>
                <thead>
                    <tr>
                        <th align='left' style='padding:10px;border-bottom:2px solid #e5e7eb;'>Nom produit</th>
                        <th align='right' style='padding:10px;border-bottom:2px solid #e5e7eb;'>Téléchargement</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
            </table>
            <p style='margin-top:20px;'>
                <a href='{$lienAchats}' style='background:#111827;color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none;display:inline-block;'>
                    Accéder à mes achats
                </a>
            </p>
        ";

        Mail::send([], [], function ($message) use ($client, $boutique, $contenu, $toEmail, $fromEmail) {
            $message->to($toEmail, $client->nom)
                ->from($fromEmail, $boutique->nom)
                ->subject('Vos fichiers - ' . $boutique->nom)
                ->html($contenu);
        });
    }

    /**
     * Envoie le fichier gratuit au lead + upsell intégré dans l'email.
     */
    public function envoyerLeadMagnet(
        \App\Models\Client   $client,
        \App\Models\Produit  $produit,
        \App\Models\Achat    $achat,
        \App\Models\Boutique $boutique
    ): void {
        $toEmail   = $this->normalizeEmail($client->email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) return;

        $downloadUrl = route('client.telechargement', ['achat' => $achat->id]);
        $annee       = date('Y');

        // Récupérer le premier upsell actif pour ce produit
        $upsell        = $produit->upsells()->where('est_actif', true)->with('produitUpsell')->first();
        $upsellHtml    = '';

        if ($upsell && $upsell->produitUpsell) {
            $pu          = $upsell->produitUpsell;
            $prixUpsell  = $upsell->prix_special !== null
                ? number_format($upsell->prix_special, 0, ',', ' ')
                : number_format($pu->prix, 0, ',', ' ');
            $lienUpsell  = route('boutique.checkout.produit', ['id' => $pu->id]);
            $titrePrix   = $upsell->prix_special !== null && $upsell->prix_special < $pu->prix
                ? "<span style='text-decoration:line-through;color:#9ca3af;font-size:0.85rem;margin-right:6px;'>".number_format($pu->prix,0,',', ' ')." F CFA</span> <strong style='color:#16a34a;font-size:1rem;'>{$prixUpsell} F CFA</strong>"
                : "<strong style='color:#f97316;font-size:1rem;'>{$prixUpsell} F CFA</strong>";

            $upsellHtml = "
            <div style='margin-top:28px;background:linear-gradient(135deg,#fff7ed,#fffbeb);border:2px solid #fed7aa;border-radius:16px;padding:20px 24px;'>
                <p style='color:#ea580c;font-weight:700;font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;margin:0 0 8px;'>🔥 {$upsell->titre_offre}</p>
                <p style='color:#1e293b;font-weight:800;font-size:1rem;margin:0 0 4px;'>{$pu->nom}</p>
                " . ($upsell->description_offre ? "<p style='color:#64748b;font-size:0.85rem;margin:0 0 12px;'>{$upsell->description_offre}</p>" : '') . "
                <div style='margin-bottom:14px;'>{$titrePrix}</div>
                <a href='{$lienUpsell}'
                   style='display:inline-block;background:linear-gradient(135deg,#f97316,#ea580c);color:white;text-decoration:none;padding:10px 24px;border-radius:10px;font-weight:700;font-size:0.9rem;'>
                    Obtenir ce produit →
                </a>
            </div>";
        }

        $html = "
<!DOCTYPE html>
<html lang='fr'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1.0'></head>
<body style='margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;'>
  <div style='max-width:560px;margin:40px auto;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.10);'>

    <!-- En-tête -->
    <div style='background:linear-gradient(135deg,#16a34a 0%,#15803d 100%);padding:36px 32px;text-align:center;'>
      <div style='font-size:3rem;margin-bottom:10px;'>🎁</div>
      <h1 style='color:#ffffff;margin:0;font-size:1.4rem;font-weight:800;'>Votre accès gratuit est prêt !</h1>
      <p style='color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:0.9rem;'>
        Merci pour votre intérêt — {$boutique->nom}
      </p>
    </div>

    <!-- Corps -->
    <div style='padding:32px;'>
      <p style='color:#1e293b;font-size:1rem;margin:0 0 6px;'>
        Bonjour <strong>{$client->nom}</strong> 👋
      </p>
      <p style='color:#475569;font-size:0.95rem;margin:0 0 24px;line-height:1.6;'>
        Votre accès à <strong>{$produit->nom}</strong> est disponible immédiatement.
        Cliquez sur le bouton ci-dessous pour télécharger votre fichier.
      </p>

      <!-- CTA Principal -->
      <div style='text-align:center;margin:28px 0;'>
        <a href='{$downloadUrl}'
           style='display:inline-block;background:linear-gradient(135deg,#16a34a,#15803d);
                  color:#ffffff;text-decoration:none;padding:16px 40px;border-radius:50px;
                  font-weight:800;font-size:1rem;letter-spacing:0.3px;
                  box-shadow:0 4px 15px rgba(22,163,74,0.4);'>
          ⬇️ Télécharger maintenant
        </a>
      </div>

      <p style='color:#94a3b8;font-size:0.8rem;text-align:center;margin:0 0 4px;'>
        Lien valable à tout moment · Accessible depuis votre espace client
      </p>

      {$upsellHtml}
    </div>

    <!-- Pied de page -->
    <div style='background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 32px;text-align:center;'>
      <p style='color:#94a3b8;font-size:0.8rem;margin:0;'>
        {$boutique->nom} &nbsp;·&nbsp; Propulsé par <strong style='color:#667eea;'>Nafalo</strong> &nbsp;·&nbsp; &copy; {$annee}
      </p>
    </div>
  </div>
</body>
</html>";

        try {
            Mail::send([], [], function ($msg) use ($toEmail, $client, $boutique, $html) {
                $msg->to($toEmail, $client->nom)
                    ->from(config('mail.from.address', 'hello@nafalo.com'), $boutique->nom)
                    ->subject('🎁 Votre accès gratuit — ' . $boutique->nom)
                    ->html($html);
            });
        } catch (\Exception $e) {
            Log::error('Erreur email lead magnet', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Notifie le(s) marchand(s) dès qu'une vente est réalisée sur leur boutique.
     * Gère aussi les co-publications : chaque partenaire reçoit son email avec son gain.
     */
    public function envoyerNotificationVente(Transaction $transaction): void
    {
        $transaction->loadMissing(['boutique.utilisateur', 'client', 'achats.produit']);

        $boutique = $transaction->boutique;
        $marchand = $boutique->utilisateur;

        if (!$marchand) {
            return;
        }

        $produitNom = $transaction->achats->first()?->produit?->nom ?? 'Produit';
        $devise     = $boutique->configuration->devise ?? 'F CFA';
        $montantFormate = number_format((float) $transaction->montant_total, 0, ',', ' ');
        $dateFormate    = $transaction->created_at->format('Y-m-d H:i:s');
        $lienAdmin      = url('/admin/transactions/' . $transaction->id);

        // ── 1. Email au propriétaire principal ─────────────────────────────
        $gainProprietaire = $transaction->montant_marchand;

        // Vérifier si le produit a une co-publication active
        $produitPrincipal = $transaction->achats->first()?->produit;
        $copublication    = null;
        if ($produitPrincipal) {
            $copublication = $produitPrincipal->copublicationActive;
        }

        if ($copublication) {
            $gainProprietaire = $copublication->gainProprietaire((float) $transaction->montant_marchand);
        }

        $gainProprietaireFormate = number_format($gainProprietaire, 0, ',', ' ');

        $emailMarchand = $this->normalizeEmail($marchand->email);
        if ($emailMarchand) {
            $this->envoyerEmailVenteIndividuel(
                email:        $emailMarchand,
                nomMarchand:  $marchand->nom,
                boutiqueNom:  $boutique->nom,
                transactionId: $transaction->id,
                produitNom:   $produitNom,
                montantTotal: $montantFormate,
                gainNet:      $gainProprietaireFormate,
                devise:       $devise,
                date:         $dateFormate,
                lienAdmin:    $lienAdmin,
            );
        }

        // ── 2. Email au co-publicateur (si accord accepté) ─────────────────
        if ($copublication && $copublication->estAccepte()) {
            $copublicateur       = $copublication->copublicateur;
            $gainCopub           = $copublication->gainCopublicateur((float) $transaction->montant_marchand);
            $gainCopubFormate    = number_format($gainCopub, 0, ',', ' ');
            $emailCopub          = $this->normalizeEmail($copublicateur->email ?? '');

            if ($emailCopub) {
                $this->envoyerEmailVenteIndividuel(
                    email:        $emailCopub,
                    nomMarchand:  $copublicateur->nom,
                    boutiqueNom:  $boutique->nom,
                    transactionId: $transaction->id,
                    produitNom:   $produitNom,
                    montantTotal: $montantFormate,
                    gainNet:      $gainCopubFormate,
                    devise:       $devise,
                    date:         $dateFormate,
                    lienAdmin:    url('/admin/transactions'),
                );
            }
        }
    }

    /**
     * Envoie l'email de notification de vente à un marchand individuel.
     */
    private function envoyerEmailVenteIndividuel(
        string $email,
        string $nomMarchand,
        string $boutiqueNom,
        int    $transactionId,
        string $produitNom,
        string $montantTotal,
        string $gainNet,
        string $devise,
        string $date,
        string $lienAdmin,
    ): void {
        $annee = date('Y');

        $html = "
<!DOCTYPE html>
<html lang='fr'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body style='margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;'>
  <div style='max-width:580px;margin:40px auto;background:#ffffff;border-radius:20px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.10);'>

    <!-- En-tête -->
    <div style='background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:36px 32px;text-align:center;'>
      <div style='font-size:2.5rem;margin-bottom:8px;'>🎉</div>
      <h1 style='color:#ffffff;margin:0;font-size:1.4rem;font-weight:800;letter-spacing:-0.5px;'>
        Nouvelle vente réalisée !
      </h1>
      <p style='color:rgba(255,255,255,0.85);margin:8px 0 0;font-size:0.95rem;'>
        Nafalo — Votre plateforme de vente numérique
      </p>
    </div>

    <!-- Corps -->
    <div style='padding:36px 32px;'>

      <p style='color:#1e293b;font-size:1.05rem;margin:0 0 6px;'>
        Salut <strong>{$nomMarchand}</strong>,
      </p>
      <p style='color:#475569;font-size:0.95rem;margin:0 0 28px;line-height:1.6;'>
        Excellente nouvelle&nbsp;! Vous venez de réaliser une vente sur votre boutique. 🚀
      </p>

      <!-- Carte détails -->
      <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:24px;margin-bottom:28px;'>
        <p style='color:#667eea;font-weight:700;font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;margin:0 0 16px;'>
          💫 Détails de la vente
        </p>

        <table width='100%' cellpadding='0' cellspacing='0'>
          <tr>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Boutique</td>
            <td style='padding:8px 0;color:#1e293b;font-weight:600;font-size:0.9rem;text-align:right;'>{$boutiqueNom}</td>
          </tr>
          <tr style='border-top:1px solid #e2e8f0;'>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Achat #</td>
            <td style='padding:8px 0;color:#1e293b;font-weight:600;font-size:0.9rem;text-align:right;'>{$transactionId}</td>
          </tr>
          <tr style='border-top:1px solid #e2e8f0;'>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Produit</td>
            <td style='padding:8px 0;color:#1e293b;font-weight:600;font-size:0.9rem;text-align:right;'>{$produitNom}</td>
          </tr>
          <tr style='border-top:1px solid #e2e8f0;'>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Montant total</td>
            <td style='padding:8px 0;color:#1e293b;font-weight:700;font-size:0.95rem;text-align:right;'>{$devise} {$montantTotal}</td>
          </tr>
          <tr style='border-top:1px solid #e2e8f0;'>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Votre gain net</td>
            <td style='padding:8px 0;text-align:right;'>
              <span style='background:#dcfce7;color:#16a34a;font-weight:800;font-size:1rem;padding:4px 12px;border-radius:20px;'>
                {$devise} {$gainNet}
              </span>
            </td>
          </tr>
          <tr style='border-top:1px solid #e2e8f0;'>
            <td style='padding:8px 0;color:#64748b;font-size:0.9rem;'>Date</td>
            <td style='padding:8px 0;color:#1e293b;font-size:0.9rem;text-align:right;'>{$date}</td>
          </tr>
        </table>
      </div>

      <!-- Bouton CTA -->
      <div style='text-align:center;margin-bottom:28px;'>
        <a href='{$lienAdmin}'
           style='display:inline-block;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
                  color:#ffffff;text-decoration:none;padding:14px 36px;border-radius:50px;
                  font-weight:700;font-size:0.95rem;letter-spacing:0.3px;
                  box-shadow:0 4px 15px rgba(102,126,234,0.4);'>
          Voir tous les détails
        </a>
      </div>

      <p style='color:#64748b;font-size:0.9rem;text-align:center;margin:0;'>
        Continuez à faire du bon travail&nbsp;! 💪
      </p>
    </div>

    <!-- Pied de page -->
    <div style='background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 32px;text-align:center;'>
      <p style='color:#94a3b8;font-size:0.8rem;margin:0;'>
        L'équipe <strong style='color:#667eea;'>Nafalo</strong> &nbsp;·&nbsp; &copy; {$annee}
      </p>
    </div>

  </div>
</body>
</html>
        ";

        try {
            Mail::send([], [], function ($message) use ($email, $nomMarchand, $html) {
                $message->to($email, $nomMarchand)
                    ->from(config('mail.from.address', 'hello@nafalo.com'), 'Nafalo')
                    ->subject('🎉 Nouvelle vente sur votre boutique !')
                    ->html($html);
            });
        } catch (\Exception $e) {
            Log::error('Erreur notification vente marchand', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function getTemplateParDefaut($type)
    {
        if ($type === 'achat') {
            return '
                <!DOCTYPE html>
                <html>
                <head><title>Confirmation d\'achat</title></head>
                <body>
                    <h1>Merci pour votre achat !</h1>
                    <p>Bonjour {{nom_client}},</p>
                    <p>Votre paiement de {{montant}} {{devise}} a bien ete confirme.</p>
                    <p>Reference de transaction : {{reference}}</p>
                    <p>Pour telecharger vos achats, cliquez sur le lien suivant :</p>
                    <p><a href="{{lien_achats}}">Acceder a mes achats</a></p>
                    <p>Merci de votre confiance !</p>
                    <p>{{boutique_nom}}</p>
                </body>
                </html>
            ';
        }

        return '
            <!DOCTYPE html>
            <html>
            <head><title>Votre panier vous attend</title></head>
            <body>
                <h1>Votre panier vous attend !</h1>
                <p>Bonjour {{nom_client}},</p>
                <p>Vous avez laisse des articles dans votre panier.</p>
                <p>Ils sont toujours disponibles !</p>
                <p><a href="{{lien_panier}}">Finaliser ma commande</a></p>
                <p>A bientot sur {{boutique_nom}} !</p>
            </body>
            </html>
        ';
    }

    protected function remplacerVariables($template, $variables)
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }

    private function normalizeEmail(?string $email): ?string
    {
        if (!$email) {
            return null;
        }

        $email = trim($email);

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function resolveFromEmail(Boutique $boutique): string
    {
        // Toujours utiliser l'adresse .env — c'est la seule vérifiée sur Brevo
        return config('mail.from.address', 'hello@example.com');
    }
}