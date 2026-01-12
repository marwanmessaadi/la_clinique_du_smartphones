<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    protected $table = 'panier';

    protected $fillable = [
        'client_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'client_id');
    }

    public function produit()
    {
        return $this->belongsTo(Produits::class);
    }
}
