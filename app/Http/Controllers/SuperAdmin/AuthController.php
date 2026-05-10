<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email'      => $request->email,
            'mot_de_passe' => $request->password,
        ];

        // Chercher l'utilisateur superadmin
        $utilisateur = \App\Models\Utilisateur::where('email', $request->email)
            ->where('role', 'superadmin')
            ->first();

        if (!$utilisateur || !\Illuminate\Support\Facades\Hash::check($request->password, $utilisateur->mot_de_passe)) {
            return back()->with('error', 'Email ou mot de passe incorrect.');
        }

        Auth::login($utilisateur);

        return redirect()->route('superadmin.dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('superadmin.login');
    }
}