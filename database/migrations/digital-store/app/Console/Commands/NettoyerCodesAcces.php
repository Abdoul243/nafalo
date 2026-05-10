<?php

namespace App\Console\Commands;

use App\Services\CodeAcces\CodeAccesService;
use Illuminate\Console\Command;

class NettoyerCodesAcces extends Command
{
    protected $signature = 'codes:nettoyer';
    protected $description = 'Nettoie les codes d\'accès expirés';
    
    protected $codeAccesService;
    
    public function __construct(CodeAccesService $codeAccesService)
    {
        parent::__construct();
        $this->codeAccesService = $codeAccesService;
    }
    
    public function handle()
    {
        $this->info('Nettoyage des codes d\'accès expirés...');
        
        $count = $this->codeAccesService->nettoyerCodesExpires();
        
        $this->info("{$count} codes d'accès expirés nettoyés.");
    }
}