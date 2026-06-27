<?php

namespace App\Services;

use App\Models\Boutique;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotAdminService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.anthropic.com/v1/messages';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key', '');
    }

    /**
     * @param  array  $messages  [['role'=>'user'|'assistant','content'=>'...']]
     */
    public function chat(array $messages, int $boutiqueId): string
    {
        $contexte = $this->construireContexte($boutiqueId);

        $systemPrompt = <<<SYSTEM
Tu es l'assistant IA intégré à Nafalo, une plateforme SaaS africaine de vente de produits numériques.
Tu aides les marchands à gérer leur boutique en ligne, augmenter leurs ventes et comprendre leurs données.

CONTEXTE DE LA BOUTIQUE ACTUELLE :
{$contexte}

INSTRUCTIONS :
- Réponds toujours en français, de manière concise et utile.
- Si on te pose une question sur les données de la boutique, utilise le contexte fourni.
- Pour les conseils marketing ou produits, adapte-les au marché africain.
- Si tu ne sais pas quelque chose, dis-le honnêtement.
- Sois professionnel mais chaleureux.
SYSTEM;

        // Convertir l'historique au format Anthropic (alternance user/assistant obligatoire)
        $anthropicMessages = $this->normaliserMessages($messages);

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'x-api-key'         => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type'      => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => 600,
                    'system'     => $systemPrompt,
                    'messages'   => $anthropicMessages,
                ]);

            if (!$response->successful()) {
                Log::warning('ChatbotAdminService: erreur Anthropic', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return "Désolé, je rencontre une difficulté technique. Veuillez réessayer dans quelques instants.";
            }

            $body = $response->json();
            $text = $body['content'][0]['text'] ?? null;

            if (!$text) {
                Log::warning('ChatbotAdminService: réponse vide', ['body' => $body]);
                return "Je n'ai pas pu générer une réponse.";
            }

            return $text;

        } catch (\Throwable $e) {
            Log::error('ChatbotAdminService: exception', ['error' => $e->getMessage()]);
            return "Je suis temporairement indisponible. Veuillez réessayer.";
        }
    }

    /**
     * S'assure que les messages alternent bien user/assistant (requis par Anthropic).
     * Le dernier message doit être de rôle "user".
     */
    private function normaliserMessages(array $messages): array
    {
        $normalises = [];
        $dernierRole = null;

        foreach ($messages as $msg) {
            $role = $msg['role'] ?? 'user';
            // Fusionner si même rôle consécutif
            if ($role === $dernierRole && !empty($normalises)) {
                $normalises[count($normalises) - 1]['content'] .= "\n" . $msg['content'];
            } else {
                $normalises[] = ['role' => $role, 'content' => $msg['content']];
                $dernierRole = $role;
            }
        }

        // Doit commencer par "user"
        if (!empty($normalises) && $normalises[0]['role'] !== 'user') {
            array_unshift($normalises, ['role' => 'user', 'content' => '(contexte)']);
        }

        return $normalises;
    }

    private function construireContexte(int $boutiqueId): string
    {
        try {
            $boutique = Boutique::withCount(['produits', 'clients'])
                ->with('categories')
                ->find($boutiqueId);

            if (!$boutique) return "Boutique introuvable.";

            $totalVentes = Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->count();

            $revenuTotal = Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->sum('montant_total');

            $topProduits = $boutique->produits()
                ->withCount('achats as nb_ventes')
                ->orderByDesc('nb_ventes')
                ->limit(5)
                ->get()
                ->map(fn($p) => "- {$p->nom} ({$p->nb_ventes} ventes, {$p->prix} FCFA)")
                ->join("\n");

            if (!$topProduits) $topProduits = "Aucun produit.";

            $transactionsRecentes = Transaction::where('boutique_id', $boutiqueId)
                ->where('statut', Transaction::STATUT_REUSSI)
                ->latest()
                ->limit(5)
                ->get()
                ->map(fn($t) => "- {$t->created_at->format('d/m/Y')} : {$t->montant_total} FCFA")
                ->join("\n");

            if (!$transactionsRecentes) $transactionsRecentes = "Aucune transaction récente.";

            $categories = $boutique->categories->pluck('nom')->join(', ') ?: 'Aucune';

            return <<<CONTEXT
Nom de la boutique : {$boutique->nom}
Description : {$boutique->description}
Nombre de produits : {$boutique->produits_count}
Catégories : {$categories}
Nombre de clients : {$boutique->clients_count}
Ventes réussies totales : {$totalVentes}
Revenu total : {$revenuTotal} FCFA

Top produits :
{$topProduits}

5 dernières ventes :
{$transactionsRecentes}
CONTEXT;

        } catch (\Throwable $e) {
            Log::warning('ChatbotAdminService: erreur contexte', ['error' => $e->getMessage()]);
            return "Données de boutique temporairement indisponibles.";
        }
    }
}
