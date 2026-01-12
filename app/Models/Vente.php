<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_vente',
        'produit_id',
        'utilisateur_id',
        'quantite',
        'prix_unitaire',
        'prix_total',
        'statut',
        'notes',
        'date_vente',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'prix_total' => 'decimal:2',
        'date_vente' => 'datetime',
    ];

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produits::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    // Scope pour les recherches
    public function scopeSearch($query, $search)
    {
        return $query->where('numero_vente', 'like', "%$search%")
            ->orWhereHas('produit', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            })
            ->orWhereHas('utilisateur', function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%");
            });
    }

    // Scope pour filtrer par statut
    public function scopeByStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // Scope pour filtrer par période
    public function scopeByPeriode($query, $debut, $fin)
    {
        return $query->whereBetween('date_vente', [$debut, $fin]);
    }

    // Générer un numéro de vente unique
    public static function generateNumeroVente()
    {
        do {
            $numero = 'V' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('numero_vente', $numero)->exists());

        return $numero;
    }
}
