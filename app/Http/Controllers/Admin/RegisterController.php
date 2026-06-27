<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'                   => ['required', 'string', 'max:191'],
            'email'                 => ['required', 'email', 'max:191', 'unique:utilisateurs,email'],
            'password'              => ['required', 'string', Password::min(8), 'confirmed'],
            'nom_boutique'          => ['required', 'string', 'max:191'],
            'description'           => ['nullable', 'string', 'max:500'],
            'telephone'             => ['nullable', 'string', 'max:30'],
            'domaine_personnalise'  => ['nullable', 'string', 'max:100', 'unique:boutiques,domaine_personnalise'],
            'logo'                  => ['nullable', 'image', 'max:2048'],
        ], [
            'nom.required'                  => 'Votre nom est obligatoire.',
            'email.required'                => 'L\'adresse email est obligatoire.',
            'email.email'                   => 'L\'adresse email n\'est pas valide.',
            'email.unique'                  => 'Cette adresse email est déjà utilisée.',
            'password.required'             => 'Le mot de passe est obligatoire.',
            'password.confirmed'            => 'Les mots de passe ne correspondent pas.',
            'nom_boutique.required'         => 'Le nom de votre boutique est obligatoire.',
            'domaine_personnalise.unique'   => 'Ce domaine est déjà utilisé par une autre boutique.',
        ]);

        // Créer le compte marchand
        $utilisateur = Utilisateur::create([
            'nom'          => $request->nom,
            'email'        => $request->email,
            'mot_de_passe' => Hash::make($request->password),
            'role'         => 'admin',
        ]);

        // Préparer les données boutique
        $boutiqueData = [
            'nom'            => $request->nom_boutique,
            'utilisateur_id' => $utilisateur->id,
            'email'          => $request->email,
            'est_active'     => true,
            'description'    => $request->description,
            'telephone'      => $request->telephone,
        ];

        // Domaine personnalisé
        if (!empty($request->domaine_personnalise)) {
            $boutiqueData['domaine_personnalise'] = Str::slug($request->domaine_personnalise);
        }

        // Logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $boutiqueData['logo']        = file_get_contents($logo->getRealPath());
            $boutiqueData['logo_mime']   = $logo->getMimeType();
            $boutiqueData['logo_taille'] = $logo->getSize();
        }

        // Créer la boutique
        $boutique = Boutique::create($boutiqueData);

        // Configurations par défaut
        $boutique->configuration()->create([
            'devise'              => 'XOF',
            'relance_delai_jours' => 3,
        ]);

        // Connexion automatique
        Auth::guard('web')->login($utilisateur, true);
        session(['boutique_id' => $boutique->id]);

        return redirect()->route('admin.dashboard')
            ->with('success', '🎉 Bienvenue sur Nafalo ! Ta boutique "' . $boutique->nom . '" est prête à vendre.');
    }
}
