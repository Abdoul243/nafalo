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
        
        return view('boutiques.client.acces.demande-code', compact('boutique'));
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
        
        return view('boutiques.client.acces.verification-code', compact('boutique', 'email'));
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
        $domaine = request()->route('domaine');
        if (!$domaine) {
            $host = request()->getHost();
            if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
                $domaine = session('boutique_domaine');
            }
            $domaine = $domaine ?: $host;
        }
        
        return Boutique::where('domaine_personnalise', $domaine)
            ->where('est_active', true)
            ->firstOrFail();
    }
}

