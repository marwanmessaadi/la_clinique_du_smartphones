<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     * IMPORTANT: doit correspondre à votre table en base
     */
    protected $table = 'fournisseurs'; // ou 'fournisseur' selon votre base

    /**
     * Champs modifiables en masse
     */
    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'pays',
        'site_web',
        'notes',
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ============================
        RELATIONS
    ============================ */

    /**
     * Relation avec les produits
     */
    public function produits(): HasMany
    {
        return $this->hasMany(Produits::class, 'fournisseur_id');
    }

    /**
     * Relation avec les commandes
     */
    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'fournisseur_id');
    }

    /* ============================
        MÉTHODES UTILITAIRES
    ============================ */

    /**
     * Vérifie si le fournisseur a des produits
     */
    public function aProduits(): bool
    {
        return $this->produits()->count() > 0;
    }

    /**
     * Vérifie si le fournisseur a des commandes
     */
    public function aCommandes(): bool
    {
        return $this->commandes()->count() > 0;
    }

    /**
     * Récupère le nombre total de produits
     */
    public function getNombreProduitsAttribute(): int
    {
        return $this->produits()->count();
    }

    /**
     * Récupère le nombre total de commandes
     */
    public function getNombreCommandesAttribute(): int
    {
        return $this->commandes()->count();
    }

    /**
     * Récupère le montant total des commandes
     */
    public function getMontantTotalCommandesAttribute(): float
    {
        return $this->commandes()->sum('montant_total');
    }

    /* ============================
        SCOPES
    ============================ */

    /**
     * Scope pour rechercher un fournisseur
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
    }

    /**
     * Scope pour les fournisseurs avec produits
     */
    public function scopeAvecProduits($query)
    {
        return $query->whereHas('produits');
    }

    /**
     * Scope pour les fournisseurs avec commandes
     */
    public function scopeAvecCommandes($query)
    {
        return $query->whereHas('commandes');
    }

    /**
     * Scope pour trier par nom
     */
    public function scopeParNom($query, $order = 'asc')
    {
        return $query->orderBy('nom', $order);
    }
}