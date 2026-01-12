<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;
    protected $table = 'fournisseurs'; // Nom de la table dans la base de données
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
    ];

    public function produits()
    {
        return $this->hasMany(Produits::class, 'fournisseur_id');
    }
}
