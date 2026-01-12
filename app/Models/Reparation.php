<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    use HasFactory;

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
        'date_reparation' => 'datetime', // changed from 'date' to 'datetime'
        'prix' => 'decimal:2'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('nom', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('code', 'like', "%$search%");
    }
}
