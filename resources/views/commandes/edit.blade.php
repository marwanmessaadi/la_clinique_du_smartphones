@extends('layouts.app')
@section('title', 'Modifier Commande')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier Commande: {{ $commande->numero_commande }}</h1>
        <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Modifier le statut</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('commandes.update', $commande->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Statut de la commande</label>
                            <select name="statut" class="form-control" required>
                                <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="recue" {{ $commande->statut == 'recue' ? 'selected' : '' }}>
                                    Reçue
                                </option>
                                <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>
                                    Annulée
                                </option>
                            </select>
                            <div class="form-text">
                                <strong>Attention:</strong> Si vous passez à "Reçue", le stock des produits sera augmenté.
                                Si vous revenez à "En attente" ou "Annulée", le stock sera diminué.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notes (facultatif)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $commande->notes) }}</textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Statut actuel:</strong> {{ $commande->statut }}<br>
                            <strong>Montant:</strong> {{ number_format($commande->montant_total, 2) }} DH<br>
                            <strong>Produits:</strong> {{ $commande->produits->count() }} articles
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Produits dans la commande</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Qté</th>
                                    <th>Prix</th>
                                    <th>Stock actuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commande->produits as $produit)
                                <tr>
                                    <td>{{ $produit->nom }}</td>
                                    <td>{{ $produit->pivot->quantite }}</td>
                                    <td>{{ number_format($produit->pivot->prix_achat, 2) }} DH</td>
                                    <td>
                                        <span class="badge {{ $produit->quantite > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $produit->quantite }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection