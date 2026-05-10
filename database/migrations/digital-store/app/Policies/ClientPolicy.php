<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Utilisateur;

class ClientPolicy
{
    public function view(Utilisateur $utilisateur, Client $client): bool
    {
        return $utilisateur->estAdmin();
    }
}
