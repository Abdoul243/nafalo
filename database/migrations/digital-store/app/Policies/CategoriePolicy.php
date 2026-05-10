<?php

namespace App\Policies;

use App\Models\Categorie;
use App\Models\Utilisateur;

class CategoriePolicy
{
    public function view(Utilisateur $utilisateur, Categorie $categorie): bool
    {
        return $this->canAccess($utilisateur, $categorie);
    }

    public function update(Utilisateur $utilisateur, Categorie $categorie): bool
    {
        return $this->canAccess($utilisateur, $categorie);
    }

    public function delete(Utilisateur $utilisateur, Categorie $categorie): bool
    {
        return $this->canAccess($utilisateur, $categorie);
    }

    private function canAccess(Utilisateur $utilisateur, Categorie $categorie): bool
    {
        return $utilisateur->estAdmin();
    }
}
