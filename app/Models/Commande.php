<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'commande';

    /**
     * Champs modifiables en masse
     */
    protected $fillable = [
        'numero_commande',
        'fournisseur_id',
        'date_commande',
        'date_reception', // AJOUTEZ CE CHAMP
        'montant_total',
        'notes',
        'statut',
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        'date_commande' => 'datetime',
        'date_reception' => 'datetime',
        'montant_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    /**
     * Relation avec les produits
     */
    public function produits()
    {
        return $this->belongsToMany(
            Produits::class,
            'commande_produit',
            'commande_id',
            'produits_id'
        )
        ->withPivot('quantite', 'prix_achat', 'prix_vente')
        ->withTimestamps();
    }

    /**
     * Générer un numéro de commande unique
     */
    public static function genererNumeroCommande()
    {
        $year = date('Y');
        $prefix = 'CMD-' . $year . '-';
        
        $lastCommande = self::where('numero_commande', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCommande) {
            $lastNumber = intval(substr($lastCommande->numero_commande, -6));
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculer le montant total
     */
    public function calculerMontantTotal()
    {
        $total = 0;
        
        foreach ($this->produits as $produit) {
            $quantite = $produit->pivot->quantite ?? 0;
            $prixAchat = $produit->pivot->prix_achat ?? 0;
            $total += $quantite * $prixAchat;
        }

        $this->montant_total = $total;
        $this->save();

        return $total;
    }

    /**
     * Vérifier si la commande est reçue
     */
    public function estRecue()
    {
        return $this->statut === 'recue';
    }

    /**
     * Vérifier si la commande est en attente
     */
    public function estEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la commande est annulée
     */
    public function estAnnulee()
    {
        return $this->statut === 'annulee';
    }

    /**
     * Récupérer le nombre total de produits dans la commande
     */
    public function getNombreProduitsAttribute()
    {
        return $this->produits->count();
    }

    /**
     * Récupérer la quantité totale de produits dans la commande
     */
    public function getQuantiteTotaleAttribute()
    {
        return $this->produits->sum(function($produit) {
            return $produit->pivot->quantite;
        });
    }

    /**
     * Scope pour les commandes reçues
     */
    public function scopeRecues($query)
    {
        return $query->where('statut', 'recue');
    }

    /**
     * Scope pour les commandes en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les commandes annulées
     */
    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annulee');
    }

    /**
     * Scope pour les commandes d'un fournisseur spécifique
     */
    public function scopeParFournisseur($query, $fournisseurId)
    {
        return $query->where('fournisseur_id', $fournisseurId);
    }

    /**
     * Scope pour les commandes entre deux dates
     */
    public function scopeEntreDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_commande', [$dateDebut, $dateFin]);
    }
}