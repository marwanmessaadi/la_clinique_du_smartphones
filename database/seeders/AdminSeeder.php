<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si l'admin existe déjà
        if (!Utilisateur::where('email', 'admin@clinique.com')->exists()) {
            Utilisateur::create([
                'nom' => 'Admin',
                'prenom' => 'Super',
                'email' => 'admin@clinique.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'telephone' => '0600000000',
            ]);
            
            $this->command->info('✅ Admin créé avec succès');
        } else {
            $this->command->info('ℹ️  Admin existe déjà');
        }
    }
}