<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProduitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $isCreate  = $this->isMethod('POST') && !$this->route('produit');
        $isGratuit = $this->input('type') === 'gratuit';

        // Une formation n'exige pas de fichier téléchargeable.
        $isFormation = $this->input('format') === 'formation';

        return [
            'categorie_id'       => 'nullable|exists:categories,id',
            'nom'                => 'required|string|max:255',
            'description'        => 'nullable|string',
            'type'               => 'nullable|in:payant,gratuit',
            'prix'               => $isGratuit ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'image'              => 'nullable|image|max:2048',
            'fichier'            => (($isCreate && !$isFormation) ? 'required' : 'nullable') . '|file|mimes:pdf,zip,mp3,mp4,docx,xlsx|max:102400',
            'est_publie'         => 'boolean',
            // Format de livraison + type d'accès
            'format'                => 'nullable|in:fichier,formation',
            'acces_type'            => 'nullable|in:unique,abonnement',
            'abonnement_intervalle' => 'nullable|in:mensuel,annuel',
            // Lead Magnet
            'lead_champs_requis'   => 'nullable|array',
            'lead_champs_requis.*' => 'in:telephone,ville,profession,pays',
            'lead_limite_dl'       => 'nullable|integer|min:1',
        ];
    }
    
    public function messages()
    {
        return [
            'fichier.required' => 'Le fichier du produit est requis.',
            'fichier.mimes' => 'Le fichier doit être de type : pdf, zip, mp3 ou mp4.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 10 Mo.'
        ];
    }
}