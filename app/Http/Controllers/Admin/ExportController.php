<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /** Export produits en CSV */
    public function produits()
    {
        $boutiqueId = session('boutique_id');
        $produits   = Produit::where('boutique_id', $boutiqueId)
            ->with('categorie')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'nafalo-produits-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function () use ($produits) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['ID', 'Nom', 'Catégorie', 'Prix (FCFA)', 'Ventes', 'Statut', 'Date création'], ';');

            foreach ($produits as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->nom,
                    $p->categorie->nom ?? 'Sans catégorie',
                    $p->prix,
                    $p->nb_ventes ?? 0,
                    $p->est_publie ? 'Publié' : 'Brouillon',
                    $p->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** Export clients en CSV */
    public function clients()
    {
        $boutiqueId = session('boutique_id');
        $clients    = Client::where('boutique_id', $boutiqueId)
            ->withCount('achats')
            ->orderByDesc('created_at')
            ->get();

        $filename = 'nafalo-clients-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function () use ($clients) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['ID', 'Nom', 'Email', 'Téléphone', 'Nombre d\'achats', 'Date inscription'], ';');

            foreach ($clients as $c) {
                fputcsv($handle, [
                    $c->id,
                    $c->nom ?? '—',
                    $c->email,
                    $c->telephone ?? '—',
                    $c->achats_count,
                    $c->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** Export transactions en CSV */
    public function transactions()
    {
        $boutiqueId   = session('boutique_id');
        $transactions = Transaction::where('boutique_id', $boutiqueId)
            ->with(['client', 'achats.produit'])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'nafalo-transactions-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'Référence', 'Client', 'Email client', 'Produits',
                'Montant total (FCFA)', 'Commission Nafalo (FCFA)',
                'Montant net (FCFA)', 'Statut', 'Moyen de paiement', 'Date',
            ], ';');

            foreach ($transactions as $t) {
                $produits = $t->achats->map(fn($a) => $a->produit->nom ?? '?')->join(', ');
                fputcsv($handle, [
                    $t->reference,
                    $t->client->nom ?? '—',
                    $t->client->email ?? '—',
                    $produits,
                    $t->montant_total,
                    $t->commission ?? round($t->montant_total * 0.05),
                    $t->montant_marchand ?? round($t->montant_total * 0.95),
                    ucfirst(str_replace('_', ' ', $t->statut)),
                    $t->moyen_paiement ?? '—',
                    $t->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
