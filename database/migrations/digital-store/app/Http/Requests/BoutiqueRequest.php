<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoutiqueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $boutiqueId = $this->route('boutique') ? $this->route('boutique')->id : null;
        
        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'reseaux_sociaux' => 'nullable|array',
            'domaine_personnalise' => 'nullable|string|max:191|unique:boutiques,domaine_personnalise,' . $boutiqueId,
            'est_active' => 'boolean'
        ];
    }
}