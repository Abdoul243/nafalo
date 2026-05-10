<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE boutiques MODIFY logo VARCHAR(500) NULL');
        
        $columns = DB::select("SHOW COLUMNS FROM boutiques LIKE 'logo_mime'");
        if (empty($columns)) {
            DB::statement('ALTER TABLE boutiques ADD logo_mime VARCHAR(100) NULL');
            DB::statement('ALTER TABLE boutiques ADD logo_taille INT NULL');
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE boutiques MODIFY logo LONGBLOB NULL');
    }
};