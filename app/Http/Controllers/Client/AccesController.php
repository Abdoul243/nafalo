<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Boutique;
use App\Models\Client;
use App\Services\CodeAcces\CodeAccesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccesController extends Controller
{
    protected $codeAccesService;
    
    public function __construct(CodeAccesService $codeAccesService)
    {
        $this->codeAccesService = $codeAccesService;
    }
    
    public function demande()
    {
        $boutique = $this->getBoutique();
        
        return view('boutique.client.acces.demande-code', compact('boutique'));
    }
    
    public function envoyerCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);
        
        $boutique = $this->getBoutique();
        
        $client = Client::where('boutique_id', $boutique->id)
            ->where('email', $validated['email'])
            ->first();
            
        if (!$client) {
            return redirect()->back()
                ->with('error', 'Aucun compte trouvé avec cet email.');
        }
        
        // Générer et envoyer le code
        $code = $this->codeAccesService->genererEtEnvoyerCode($client);
        
        Session::put('code_verification_email_' . $boutique->id, $client->email);
        
        return redirect()->route('client.acces.verification')
            ->with('success', 'Un code de vérification vous a été envoyé par email.');
    }
    
    public function verification()
    {
        $boutique = $this->getBoutique();
        $email = Session::get('code_verification_email_' . $boutique->id);
        
        if (!$email) {
            return redirect()->route('client.acces.demande');
        }
        
        return view('boutique.client.acces.verification-code', compact('boutique', 'email'));
    }
    
    public function verifierCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6'
        ]);
        
        $boutique = $this->getBoutique();
        $email = Session::get('code_verification_email_' . $boutique->id);
        
        if (!$email) {
            return redirect()->route('client.acces.demande');
        }
        
        $client = Client::where('boutique_id', $boutique->id)
            ->where('email', $email)
            ->first();
            
        if (!$client || !$client->aUnCodeValide() || $client->code_acces !== $validated['code']) {
            return redirect()->back()
                ->with('error', 'Code invalide ou expiré.');
        }
        
        // Nettoyer le code utilisé
        $client->update([
            'code_acces' => null,
            'code_expire_at' => null
        ]);
        
        Session::put('client_acces_' . $boutique->id, $client->email);
        Session::forget('code_verification_email_' . $boutique->id);
        
        return redirect()->route('client.mes-achats.index')
            ->with('success', 'Connexion réussie !');
    }
    
    public function deconnexion()
    {
        $boutique = $this->getBoutique();
        
        Session::forget('client_acces_' . $boutique->id);
        
        return redirect()->route('boutique.accueil')
            ->with('success', 'Vous avez été déconnecté.');
    }
    
    protected function getBoutique()
    {
        static $localHosts = ['127.0.0.1', 'localhost', 'nafalo.test', 'nafalo.local'];
        $request = request();
        $host    = $request->getHost();

        // 1. Domaine en route
        $domaine = $request->route('domaine');
        if ($domaine) {
            $b = Boutique::where('domaine_personnalise', $domaine)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 2. Hôte production
        if (!in_array($host, $localHosts, true)) {
            $b = Boutique::where('domaine_personnalise', $host)->where('est_active', true)->first();
            if ($b) return $b;
        }

        // 3. Local : boutique_id (sélection admin, fiable) → domaine → première active
        if (session('boutique_id')) {
            $b = Boutique::where('id', session('boutique_id'))->where('est_active', true)->first();
            if ($b) return $b;
        }
        if (session('boutique_domaine')) {
            $b = Boutique::where('domaine_personnalise', session('boutique_domaine'))->where('est_active', true)->first();
            if ($b) return $b;
        }

        return Boutique::where('est_active', true)->firstOrFail();
    }
}