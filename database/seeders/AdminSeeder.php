<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('utilisateurs')->insert([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@clinique.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'telephone' => '0600000000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
