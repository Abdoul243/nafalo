<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Relance email des paniers abandonnés / paiements échoués ──
// Tourne chaque minute ; relance les transactions non finalisées > 5 min.
Schedule::command('boutique:nettoyer-paniers')
    ->everyMinute()
    ->withoutOverlapping();

// Nettoyage des codes d'accès expirés (toutes les heures)
Schedule::command('codes:nettoyer')
    ->hourly()
    ->withoutOverlapping();
