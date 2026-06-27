<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CollaborationSearchService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', '');
    }

    /**
     * Analyse une requête en langage naturel et extrait les niches,
     * mots-clés et intentions de collaboration.
     */
    public function analyserRequete(string $query): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'x-api-key'         => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => 400,
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => $this->construirePrompt($query),
                    ]],
                ]);

            if (!$response->successful()) {
                return $this->fallbackAnalyse($query);
            }

            $body  = $response->json();
            $texte = $body['content'][0]['text'] ?? '{}';
            $texte = preg_replace('/```(?:json)?\n?|\n?```/', '', trim($texte));
            $resultat = json_decode($texte, true);

            return is_array($resultat) ? $resultat : $this->fallbackAnalyse($query);

        } catch (\Throwable $e) {
            Log::warning('CollaborationSearchService: API Claude inaccessible', [
                'error' => $e->getMessage(),
                'query' => $query,
            ]);
            return $this->fallbackAnalyse($query);
        }
    }

    private function construirePrompt(string $query): string
    {
        return <<<PROMPT
Tu es un assistant expert en e-commerce africain spécialisé dans les niches de contenu numérique.

Analyse cette requête de recherche de partenaire de co-publication :
"$query"

Retourne UNIQUEMENT un JSON valide (sans markdown, sans texte avant/après) avec exactement cette structure :
{
  "niches": ["mot-clé1", "mot-clé2", "mot-clé3"],
  "niches_complementaires": ["niche qui s'associe bien", "autre niche"],
  "mots_cles_produits": ["type de produit", "autre type"],
  "resume": "Ce que cherche l'utilisateur en 1 phrase courte",
  "emoji": "un emoji représentant la niche principale"
}

Règles :
- "niches" : 2 à 5 mots-clés tirés directement de la requête (en français, minuscules)
- "niches_complementaires" : 1 à 3 niches qui collaborent naturellement avec la niche principale
- "mots_cles_produits" : types de produits numériques liés (e-book, formation, guide, template, etc.)
- "resume" : phrase courte et claire
- "emoji" : 1 seul emoji pertinent

Exemples de niches africaines : agriculture, élevage, aviculture, pêche, maraîchage, artisanat,
mode africaine, beauté naturelle, cuisine africaine, santé naturelle, éducation, finance personnelle,
immobilier, entrepreneuriat, technologie, musique, sport, bien-être, développement personnel.
PROMPT;
    }

    private function fallbackAnalyse(string $query): array
    {
        $mots = array_filter(
            array_map('trim', explode(' ', strtolower($query))),
            fn($m) => strlen($m) > 2
        );

        return [
            'niches'                 => array_values(array_slice($mots, 0, 3)),
            'niches_complementaires' => [],
            'mots_cles_produits'     => [],
            'resume'                 => $query,
            'emoji'                  => '🔍',
        ];
    }
}
