<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodePromoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'code' => 'required|string|max:191|unique:codes_promo,code,' . $this->route('codePromo')?->id . ',id,boutique_id,' . session('boutique_id'),
            'type_reduction' => 'required|in:fixe,pourcentage',
            'valeur_reduction' => 'required|numeric|min:0',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'utilisation_max' => 'nullable|integer|min:1',
            'est_actif' => 'boolean',
            'produits' => 'nullable|array',
            'produits.*' => 'exists:produits,id'
        ];
    }
}