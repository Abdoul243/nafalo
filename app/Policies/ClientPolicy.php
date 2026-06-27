<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Utilisateur;

class ClientPolicy
{
    public function view(Utilisateur $utilisateur, Client $client): bool
    {
        // Le client doit appartenir à une boutique de l'utilisateur.
        return $utilisateur->estAdmin()
            && $utilisateur->boutiques()->whereKey($client->boutique_id)->exists();
    }
}
