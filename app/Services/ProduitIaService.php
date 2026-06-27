<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProduitIaService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', '');
    }

    // ─────────────────────────────────────────────────────────
    //  FEATURE 1 — Génération de page de vente
    // ─────────────────────────────────────────────────────────

    public function genererPageVente(string $description, string $categorie, string $type = 'payant'): array
    {
        $prompt = <<<PROMPT
Tu es un copywriter expert spécialisé dans la vente de produits numériques sur le marché africain francophone.

Un marchand africain vend le produit suivant :
- Description brève : "{$description}"
- Catégorie : {$categorie}
- Type : {$type}

Génère une page de vente complète et persuasive. Retourne UNIQUEMENT un JSON valide avec cette structure exacte :
{
  "titre": "Titre accrocheur et vendeur (max 70 caractères)",
  "sous_titre": "Sous-titre qui clarifie la promesse (max 120 caractères)",
  "description_html": "Description complète en HTML avec <h3>, <ul>, <li>, <strong>, <p>. Inclure : problème résolu, à qui s'adresse ce produit, ce qu'ils vont apprendre/obtenir, pourquoi maintenant. Minimum 200 mots.",
  "bullets": ["bénéfice 1", "bénéfice 2", "bénéfice 3", "bénéfice 4", "bénéfice 5"],
  "faq": [
    {"question": "Question fréquente 1", "reponse": "Réponse claire et rassurante"},
    {"question": "Question fréquente 2", "reponse": "Réponse claire et rassurante"},
    {"question": "Question fréquente 3", "reponse": "Réponse claire et rassurante"}
  ],
  "cta": "Texte du bouton d'achat (ex: Obtenir ma formation maintenant)",
  "urgence": "Message d'urgence ou de rareté court (ex: Plus que 12 exemplaires au prix actuel)"
}

Adapte le ton et les exemples au contexte africain. Utilise des formulations percutantes.
PROMPT;

        return $this->appelerClaude($prompt, 1500) ?? [
            'titre' => '',
            'sous_titre' => '',
            'description_html' => '',
            'bullets' => [],
            'faq' => [],
            'cta' => '',
            'urgence' => '',
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  FEATURE 6 — Traduction & adaptation culturelle
    // ─────────────────────────────────────────────────────────

    public function traduireAdapter(string $nom, string $description, string $langue): array
    {
        $langues = [
            'en'  => 'anglais international (adapté à l\'Afrique anglophone : Nigeria, Ghana, Kenya)',
            'sw'  => 'swahili (Afrique de l\'Est : Kenya, Tanzanie, Ouganda)',
            'ha'  => 'haoussa (Afrique de l\'Ouest : Nord Nigeria, Niger, Tchad)',
            'pt'  => 'portugais (adapté à l\'Afrique lusophone : Angola, Mozambique, Cap-Vert)',
            'ar'  => 'arabe (adapté au Maghreb et Afrique du Nord)',
        ];

        $cible = $langues[$langue] ?? 'anglais';

        $prompt = <<<PROMPT
Tu es un expert en adaptation culturelle pour les marchés africains.

Traduis et adapte culturellement ce produit numérique en {$cible} :

Nom original : "{$nom}"
Description originale : "{$description}"

Adapte non seulement la langue mais aussi :
- Les exemples et références culturelles
- Le ton (direct, communautaire, respectueux selon la culture)
- Les expressions idiomatiques locales
- Les références monétaires si mentionnées

Retourne UNIQUEMENT un JSON valide :
{
  "nom": "Nom traduit et adapté",
  "description": "Description traduite et adaptée culturellement (même longueur que l'original)",
  "notes_adaptation": "Ce qui a été adapté culturellement en 1-2 phrases"
}
PROMPT;

        return $this->appelerClaude($prompt, 800) ?? [
            'nom' => $nom,
            'description' => $description,
            'notes_adaptation' => 'Traduction non disponible.',
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  FEATURE 7 — Score de compatibilité partenaire
    // ─────────────────────────────────────────────────────────

    public function scorerCompatibilite(array $boutique1, array $boutique2): array
    {
        $prompt = <<<PROMPT
Tu es un expert en stratégie de co-publication de produits numériques sur le marché africain.

Évalue la compatibilité entre ces deux boutiques pour une co-publication :

BOUTIQUE A (propriétaire) :
- Nom : {$boutique1['nom']}
- Catégories : {$boutique1['categories']}
- Produits : {$boutique1['produits']}
- Ventes totales : {$boutique1['ventes']}

BOUTIQUE B (partenaire potentiel) :
- Nom : {$boutique2['nom']}
- Catégories : {$boutique2['categories']}
- Produits : {$boutique2['produits']}
- Ventes totales : {$boutique2['ventes']}

Retourne UNIQUEMENT un JSON valide :
{
  "score": 85,
  "niveau": "Excellent",
  "raison_courte": "Niches complémentaires avec audiences qui se recoupent",
  "points_forts": ["point fort 1", "point fort 2"],
  "point_attention": "Un risque ou point à surveiller"
}

Règles pour le score :
- 85-100 : Excellent (niches très complémentaires, audiences similaires)
- 65-84  : Bon (bonne synergie probable)
- 45-64  : Moyen (collaboration possible mais limitée)
- 0-44   : Faible (peu de synergie)
PROMPT;

        return $this->appelerClaude($prompt, 300) ?? [
            'score'         => 50,
            'niveau'        => 'Moyen',
            'raison_courte' => 'Analyse indisponible',
            'points_forts'  => [],
            'point_attention' => '',
        ];
    }

    // ─────────────────────────────────────────────────────────
    //  Appel commun à l'API Anthropic
    // ─────────────────────────────────────────────────────────

    private function appelerClaude(string $prompt, int $maxTokens): ?array
    {
        try {
            $response = Http::timeout(25)
                ->withHeaders([
                    'x-api-key'         => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => $maxTokens,
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => $prompt,
                    ]],
                ]);

            if (!$response->successful()) {
                Log::warning('ProduitIaService: erreur API', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            $body  = $response->json();
            $texte = $body['content'][0]['text'] ?? null;

            if (!$texte) return null;

            $texte = preg_replace('/```(?:json)?\n?|\n?```/', '', trim($texte));
            $data  = json_decode($texte, true);

            return is_array($data) ? $data : null;

        } catch (\Throwable $e) {
            Log::error('ProduitIaService: exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
