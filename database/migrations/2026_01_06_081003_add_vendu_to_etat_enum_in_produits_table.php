<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE produits MODIFY COLUMN etat ENUM('disponible', 'indisponible', 'vendu') DEFAULT 'disponible'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE produits MODIFY COLUMN etat ENUM('disponible', 'indisponible') DEFAULT 'disponible'");
    }
};
