<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ChatbotAdminService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message'  => 'required|string|min:1|max:1000',
            'history'  => 'nullable|array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $boutiqueId = session('boutique_id');
        if (!$boutiqueId) {
            return response()->json(['reply' => 'Veuillez d\'abord sélectionner une boutique.']);
        }

        // Construire l'historique + nouveau message
        $messages = array_merge(
            $request->input('history', []),
            [['role' => 'user', 'content' => $request->input('message')]]
        );

        // Limiter l'historique à 10 échanges pour rester rapide
        if (count($messages) > 20) {
            $messages = array_slice($messages, -20);
        }

        $service = new ChatbotAdminService();
        $reply   = $service->chat($messages, $boutiqueId);

        return response()->json(['reply' => $reply ?: "Je n'ai pas pu générer une réponse. Réessayez."]);
    }
}
