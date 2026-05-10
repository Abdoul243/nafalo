<?php

namespace App\Policies;

use App\Models\Produit;
use App\Models\Utilisateur;

class ProduitPolicy
{
    public function view(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $utilisateur->estAdmin();
    }

    public function update(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $utilisateur->estAdmin();
    }

    public function delete(Utilisateur $utilisateur, Produit $produit): bool
    {
        return $utilisateur->estAdmin();
    }
}
