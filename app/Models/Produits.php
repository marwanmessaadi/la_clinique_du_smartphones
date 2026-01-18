<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produits extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'produits';

    /**
     * Champs modifiables en masse
     */
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
        'produit_parent_id', // AJOUTEZ CE CHAMP POUR LES VARIANTES
        'marque', // SI VOUS AVEZ CE CHAMP
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'prix_gros' => 'decimal:2',
        'quantite' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_achat' => 'datetime',
        'date_ajout' => 'datetime',
    ];

    /* ============================
        RELATIONS
    ============================ */

    /**
     * Relation avec la catégorie
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * Relation avec les ventes (POS)
     */
    public function ventes(): BelongsToMany
    {
        return $this->belongsToMany(Vente::class, 'vente_produit')
            ->withPivot('quantite', 'prix_vente')
            ->withTimestamps();
    }

    /**
     * Relation avec les commandes
     */
    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(
            Commande::class,
            'commande_produit',
            'produits_id',
            'commande_id'
        )->withPivot('quantite', 'prix_achat', 'prix_vente')
         ->withTimestamps();
    }

    /**
     * Relation avec le produit parent (pour les variantes)
     */
    public function produitParent(): BelongsTo
    {
        return $this->belongsTo(Produits::class, 'produit_parent_id');
    }

    /**
     * Relation avec les variantes
     */
    public function variantes()
    {
        return $this->hasMany(Produits::class, 'produit_parent_id');
    }

    /* ============================
        MÉTHODES UTILITAIRES
    ============================ */

    /**
     * Vérifie si le produit est en stock
     */
    public function estEnStock(): bool
    {
        return $this->quantite > 0;
    }

    /**
     * Vérifie si le produit est disponible
     */
    public function estDisponible(): bool
    {
        return $this->etat === 'disponible' && $this->estEnStock();
    }

    /**
     * Vérifie si le produit est une variante
     */
    public function estVariante(): bool
    {
        return !is_null($this->produit_parent_id);
    }

    /**
     * Calcule la marge bénéficiaire
     */
    public function getMargeAttribute(): float
    {
        return $this->prix_vente - $this->prix_achat;
    }

    /**
     * Calcule le taux de marge
     */
    public function getTauxMargeAttribute(): float
    {
        if ($this->prix_achat > 0) {
            return (($this->prix_vente - $this->prix_achat) / $this->prix_achat) * 100;
        }
        return 0;
    }

    /**
     * Augmente le stock
     */
    public function augmenterStock(int $quantite): bool
    {
        $this->quantite += $quantite;
        return $this->save();
    }

    /**
     * Diminue le stock
     */
    public function diminuerStock(int $quantite): bool
    {
        if ($this->quantite >= $quantite) {
            $this->quantite -= $quantite;
            return $this->save();
        }
        return false;
    }

    /**
     * Génère un code produit unique
     */
    public static function genererCodeUnique(string $prefix = 'PRD'): string
    {
        do {
            $code = $prefix . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);
            $exists = self::where('code', $code)->exists();
        } while ($exists);

        return $code;
    }

    /**
     * Crée une variante du produit
     */
    public function creerVariante(array $data): Produits
    {
        $variante = new Produits([
            'nom' => $data['nom'] ?? $this->nom . ' (Variante)',
            'code' => $data['code'] ?? self::genererCodeUnique('VAR'),
            'description' => $data['description'] ?? $this->description,
            'prix_achat' => $data['prix_achat'] ?? $this->prix_achat,
            'prix_vente' => $data['prix_vente'] ?? $this->prix_vente,
            'prix_gros' => $data['prix_gros'] ?? $this->prix_gros,
            'quantite' => $data['quantite'] ?? 0,
            'categorie_id' => $data['categorie_id'] ?? $this->categorie_id,
            'fournisseur_id' => $data['fournisseur_id'] ?? $this->fournisseur_id,
            'produit_parent_id' => $this->id,
            'etat' => $data['etat'] ?? 'disponible',
            'type' => $data['type'] ?? $this->type,
        ]);

        $variante->save();
        return $variante;
    }

    /* ============================
        SCOPES
    ============================ */

    /**
     * Scope pour les produits en stock
     */
    public function scopeEnStock($query)
    {
        return $query->where('quantite', '>', 0);
    }

    /**
     * Scope pour les produits disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('etat', 'disponible')->enStock();
    }

    /**
     * Scope pour les produits d'une catégorie
     */
    public function scopeParCategorie($query, $categorieId)
    {
        return $query->where('categorie_id', $categorieId);
    }

    /**
     * Scope pour les produits d'un fournisseur
     */
    public function scopeParFournisseur($query, $fournisseurId)
    {
        return $query->where('fournisseur_id', $fournisseurId);
    }

    /**
     * Scope pour les produits par prix
     */
    public function scopeParPrix($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('prix_vente', '>=', $min);
        }
        if ($max) {
            $query->where('prix_vente', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope pour les produits par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour les produits principaux (non variantes)
     */
    public function scopePrincipaux($query)
    {
        return $query->whereNull('produit_parent_id');
    }

    /**
     * Scope pour rechercher par nom ou code
     */
    public function scopeRechercher($query, $search)
    {
        return $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }
}