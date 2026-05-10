<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom'              => ['required', 'string', 'max:191'],
            'email'            => ['required', 'email', 'max:191', 'unique:utilisateurs,email'],
            'password'         => ['required', 'string', Password::min(8), 'confirmed'],
            'nom_boutique'     => ['required', 'string', 'max:191'],
        ], [
            'nom.required'          => 'Votre nom est obligatoire.',
            'email.required'        => 'L\'adresse email est obligatoire.',
            'email.email'           => 'L\'adresse email n\'est pas valide.',
            'email.unique'          => 'Cette adresse email est déjà utilisée.',
            'password.required'     => 'Le mot de passe est obligatoire.',
            'password.confirmed'    => 'Les mots de passe ne correspondent pas.',
            'nom_boutique.required' => 'Le nom de votre boutique est obligatoire.',
        ]);

        // Créer le compte marchand
        $utilisateur = Utilisateur::create([
            'nom'          => $request->nom,
            'email'        => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'role'         => 'admin',
        ]);

        // Créer la boutique
        $boutique = Boutique::create([
            'nom'            => $request->nom_boutique,
            'utilisateur_id' => $utilisateur->id,
            'email'          => $request->email,
            'est_active'     => true,
        ]);

        // Connexion automatique
        Auth::guard('web')->login($utilisateur, true);
        session(['boutique_id' => $boutique->id]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Bienvenue sur Nafalo ! Votre boutique "' . $boutique->nom . '" est prête.');
    }
}
