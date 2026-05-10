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
        $toEmail = $this->normalizeEmail($email);
        $fromEmail = $this->resolveFromEmail($boutique);

        if (!$toEmail) {
            Log::warning('Cart reminder ignored: invalid email', [
                'email' => $email,
                'boutique_id' => $boutique->id ?? null,
            ]);
            return;
        }

        $template = $boutique->configuration->email_template_relance
            ?? $this->getTemplateParDefaut('relance');

        $contenu = $this->remplacerVariables($template, [
            'nom_client' => $nom,
            'boutique_nom' => $boutique->nom,
            'lien_panier' => route('boutique.panier.index'),
        ]);

        Mail::send([], [], function ($message) use ($nom, $boutique, $contenu, $toEmail, $fromEmail) {
            $message->to($toEmail, $nom)
                ->from($fromEmail, $boutique->nom)
                ->subject('Votre panier vous attend - ' . $boutique->nom)
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
        $candidates = [
            $boutique->configuration->email_expediteur ?? null,
            $boutique->email ?? null,
            config('mail.from.address'),
        ];

        foreach ($candidates as $candidate) {
            $email = $this->normalizeEmail($candidate);
            if ($email) {
                return $email;
            }
        }

        return 'hello@example.com';
    }
}
