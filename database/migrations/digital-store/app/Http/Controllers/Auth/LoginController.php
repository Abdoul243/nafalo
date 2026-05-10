<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.admin.login');
    }

    /**
     * Traite la tentative de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $utilisateur = Auth::guard('web')->user();
            
            // Initialiser la boutique active en session
            $boutiqueId = Boutique::query()->orderBy('id')->value('id');
            if ($boutiqueId) {
                session(['boutique_id' => $boutiqueId]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Bienvenue ' . $utilisateur->nom . ' !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        // Récupérer le nom de l'utilisateur pour le message
        $userName = Auth::guard('web')->user()->nom ?? 'Utilisateur';
        
        // Déconnexion
        Auth::guard('web')->logout();

        // Invalider la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Supprimer toutes les données de session liées à la boutique
        Session::forget('boutique_id');

        // Rediriger vers la page de login avec un message
        return redirect()->route('admin.login')
            ->with('status', 'À bientôt ' . $userName . ' ! Vous avez été déconnecté avec succès.');
    }
}
