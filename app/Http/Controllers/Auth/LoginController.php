<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $boutiques = Boutique::orderBy('nom')->get();

            // Si une seule boutique → aller directement au dashboard
            if ($boutiques->count() === 1) {
                session(['boutique_id' => $boutiques->first()->id]);
                return redirect()->route('admin.dashboard');
            }

            // Si plusieurs boutiques → page de sélection
            if ($boutiques->count() > 1) {
                return redirect()->route('admin.boutiques.choisir');
            }

            // Aucune boutique → dashboard quand même
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $userName = Auth::guard('web')->user()->nom ?? 'Utilisateur';

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget('boutique_id');

        return redirect()->route('admin.login')
            ->with('status', 'À bientôt ' . $userName . ' !');
    }
}
