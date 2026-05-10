<?php

namespace App\Services;

use App\Models\NotificationMarchand;
use App\Models\Transaction;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Copublication;

class NotificationService
{
    /* ── Nouvelle vente ─────────────────────────────────────────────── */

    public static function nouvelleVente(Transaction $transaction): void
    {
        $boutique = $transaction->boutique;
        if (!$boutique) return;

        $utilisateurId = $boutique->utilisateur_id;
        if (!$utilisateurId) return;

        self::creer($utilisateurId, NotificationMarchand::TYPE_VENTE, [
            'titre'   => '💰 Nouvelle vente !',
            'message' => 'Vous avez reçu une vente de ' . number_format($transaction->montant_total, 0, ',', ' ') . ' FCFA. Gain net : ' . number_format($transaction->montant_marchand, 0, ',', ' ') . ' FCFA.',
            'lien'    => route('admin.transactions.show', $transaction->id),
            'data'    => ['transaction_id' => $transaction->id, 'montant' => $transaction->montant_total],
        ]);
    }

    /* ── Nouvel avis ────────────────────────────────────────────────── */

    public static function nouvelAvis(int $utilisateurId, int $avisId, string $produitNom, int $note): void
    {
        $etoiles = str_repeat('⭐', $note);
        self::creer($utilisateurId, NotificationMarchand::TYPE_AVIS, [
            'titre'   => '⭐ Nouvel avis client',
            'message' => "Un client a laissé un avis {$etoiles} sur votre produit « {$produitNom} ».",
            'lien'    => route('admin.avis.index'),
            'data'    => ['avis_id' => $avisId, 'note' => $note],
        ]);
    }

    /* ── Nouveau lead ───────────────────────────────────────────────── */

    public static function nouveauLead(int $utilisateurId, string $produitNom, Client $client): void
    {
        self::creer($utilisateurId, NotificationMarchand::TYPE_LEAD, [
            'titre'   => '🎯 Nouveau lead capturé',
            'message' => "{$client->nom} ({$client->email}) vient de télécharger votre lead magnet « {$produitNom} ».",
            'lien'    => route('admin.clients.index'),
            'data'    => ['client_id' => $client->id],
        ]);
    }

    /* ── Invitation co-publication reçue ────────────────────────────── */

    public static function invitationCopublication(Copublication $copub): void
    {
        // Notifier le copublicateur invité
        self::creer($copub->copublicateur_id, NotificationMarchand::TYPE_COPUB_INVITATION, [
            'titre'   => '🤝 Invitation co-publication',
            'message' => "Vous avez reçu une invitation à co-publier le produit « {$copub->produit->nom} » avec " . ($copub->proprietaire->nom ?? 'un marchand') . " ({$copub->pourcentage_copublicateur}% pour vous).",
            'lien'    => route('admin.copublications.index'),
            'data'    => ['copublication_id' => $copub->id],
        ]);
    }

    /* ── Réponse co-publication ─────────────────────────────────────── */

    public static function reponseCopublication(Copublication $copub, bool $accepte): void
    {
        $statut  = $accepte ? 'accepté' : 'refusé';
        $emoji   = $accepte ? '✅' : '❌';
        self::creer($copub->proprietaire_id, NotificationMarchand::TYPE_COPUB_REPONSE, [
            'titre'   => "{$emoji} Co-publication {$statut}e",
            'message' => ($copub->copublicateur->nom ?? 'Le co-publicateur') . " a {$statut} votre invitation pour le produit « {$copub->produit->nom} ».",
            'lien'    => route('admin.copublications.index'),
            'data'    => ['copublication_id' => $copub->id, 'accepte' => $accepte],
        ]);
    }

    /* ── Méthode interne ─────────────────────────────────────────────── */

    private static function creer(int $utilisateurId, string $type, array $attrs): void
    {
        try {
            NotificationMarchand::create([
                'utilisateur_id' => $utilisateurId,
                'type'           => $type,
                'titre'          => $attrs['titre'],
                'message'        => $attrs['message'],
                'lien'           => $attrs['lien'] ?? null,
                'data'           => $attrs['data'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // Ne pas bloquer l'app si la notification échoue
            \Log::warning('NotificationService: ' . $e->getMessage());
        }
    }
}
