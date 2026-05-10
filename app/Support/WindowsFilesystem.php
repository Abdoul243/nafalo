<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem;

/**
 * Surcharge du Filesystem pour corriger le bug de rename() sur Windows.
 *
 * Sur Windows, rename() retourne "Accès refusé" (code 5) si le fichier
 * de destination existe déjà. Ce correctif supprime le fichier existant
 * avant le renommage, ce qui permet la recompilation des vues Blade.
 */
class WindowsFilesystem extends Filesystem
{
    /**
     * Écrire le contenu d'un fichier en remplacement atomique.
     * Corrige la race condition Windows lors de la compilation Blade.
     */
    public function replace($path, $content, $mode = null)
    {
        // Supprimer le fichier destination s'il existe (nécessaire sur Windows)
        // car rename() échoue avec "Accès refusé" si le fichier cible existe déjà.
        if ($this->exists($path)) {
            $this->delete($path);
        }

        parent::replace($path, $content, $mode);
    }
}
