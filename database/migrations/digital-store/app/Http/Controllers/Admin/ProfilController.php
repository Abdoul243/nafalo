<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        return view('admin.profil');
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('utilisateurs')->ignore($user->id)],
            'bio' => 'nullable|string|max:500'
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profil mis à jour avec succès.');
    }
    
    public function password(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->mot_de_passe)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
            'new_password_confirmation' => 'required'
        ]);
        
        $user->update([
            'mot_de_passe' => Hash::make($validated['new_password'])
        ]);
        
        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
    
    public function avatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user = auth()->user();
        
        // Supprimer l'ancien avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        // Stocker le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update(['avatar' => $path]);
        
        return back()->with('success', 'Photo de profil mise à jour avec succès.');
    }
    
    public function preferences(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'langue' => 'required|in:fr,en',
            'fuseau_horaire' => 'required|string',
            'notifications_email' => 'boolean'
        ]);
        
        // Mettre à jour les préférences
        $user->update([
            'langue' => $validated['langue'],
            'fuseau_horaire' => $validated['fuseau_horaire'],
            'notifications_email' => $request->has('notifications_email')
        ]);
        
        return back()->with('success', 'Préférences enregistrées avec succès.');
    }
}