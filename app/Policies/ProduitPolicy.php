<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\Utilisateur;

class ProduitPolicy
{
    /**
     * Le produit doit appartenir à une boutique de l'utilisateur.
     */
    private function possede(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $utilisateur->estAdmin()
            && $utilisateur->boutiques()->whereKey($produit->boutique_id)->exists();
    }

    public function view(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $this->possede($utilisateur, $produit);
    }

    public function update(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $this->possede($utilisateur, $produit);
    }

    public function delete(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $this->possede($utilisateur, $produit);
    }
}
