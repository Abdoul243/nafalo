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
        return [
            'categorie_id' => 'nullable|exists:categories,id',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'fichier' => 'required|file|mimes:pdf,zip,mp3,mp4|max:10240',
            'est_publie' => 'boolean'
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