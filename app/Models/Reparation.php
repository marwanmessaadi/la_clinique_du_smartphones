<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    use HasFactory; // RETIREZ SoftDeletes si présent

    protected $table = 'reparations';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'date_reparation',
        'produit',
        'etat',
        'code',
        'notes',
    ];

    protected $casts = [
        'date_reparation' => 'datetime',
        'prix' => 'decimal:2'
    ];

    // Ajoutez ces scopes pour éviter les erreurs
    public function scopeWithoutSoftDeletes($query)
    {
        return $query;
    }

    public static function boot()
    {
        parent::boot();

        // Désactive le scope SoftDeletes global
        static::addGlobalScope('withoutSoftDeletes', function ($builder) {
            // Ne rien faire - cela évite d'ajouter la clause deleted_at
        });
    }
}