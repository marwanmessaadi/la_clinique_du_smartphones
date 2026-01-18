<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ Vérifiez qu'il n'y a qu'UN SEUL appel à AdminSeeder
        $this->call([
            AdminSeeder::class,  // ✅ Une seule fois
            // AdminSeeder::class,  // ❌ Supprimez si dupliqué
        ]);
    }
}