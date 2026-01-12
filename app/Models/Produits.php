<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produits extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'prix_achat',
        'prix_vente',
        'prix_gros',
        'quantite',
        'image',
        'etat',
        'type',
        'categorie_id',
        'fournisseur_id',
    ];

    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'prix_gros' => 'decimal:2',
        'date_ajout' => 'datetime',
        'date_achat' => 'datetime',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);  // Déjà correct après renommage
    }
}