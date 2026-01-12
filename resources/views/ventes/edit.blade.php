@extends('layouts.app')
@section('title', 'Modifier la Vente - ' . $vente->numero_vente)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier la Vente</h1>
                    <p class="form-subtitle">Mettez à jour les informations de la vente</p>
                </div>

                <div class="form-body">
                    <!-- Informations de la vente -->
                    <div class="sale-info">
                        <div class="sale-number">{{ $vente->numero_vente }}</div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Produit:</strong> {{ $vente->produit->nom }}</p>
                                <p><strong>Quantité:</strong> {{ $vente->quantite }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Prix total:</strong> {{ number_format($vente->prix_total, 2) }} €</p>
                                <p><strong>Date:</strong> {{ $vente->date_vente->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('ventes.update', $vente) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="utilisateur_id">Client</label>
                                    <select class="form-control" id="utilisateur_id" name="utilisateur_id">
                                        <option value="">Client anonyme</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}"
                                                    {{ $vente->utilisateur_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->prenom }} {{ $client->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="statut">Statut *</label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="en_cours" {{ $vente->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="finalisee" {{ $vente->statut == 'finalisee' ? 'selected' : '' }}>Finalisée</option>
                                        <option value="annulee" {{ $vente->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                    <small class="text-muted">
                                        Attention: Changer le statut vers "Annulée" remettra le stock en inventaire.
                                        Changer vers "Finalisée" retirera la quantité du stock.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $vente->notes }}</textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour la vente
                            </button>
                            <a href="{{ route('ventes.show', $vente) }}" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
