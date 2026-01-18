@extends('layouts.app')

@section('title', 'Point de Vente (POS)')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">

    <div class="d-flex justify-content-center mb-3 gap-2">
        <button id="btn-ventes" class="btn btn-primary active">Ventes</button>
    </div>

    <div id="ventes-section">
        <div class="row">

            <div class="col-lg-7 mb-3">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Prix</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="panier-body">
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun produit</td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-end">
                    Total :
                    <strong id="total-payer">0.00</strong> DH
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-success" id="btn-especes">Espèces</button>
                    <button class="btn btn-primary" id="btn-credit">Crédit</button>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="row row-cols-2 g-2">
                    @foreach($produits as $produit)
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-body p-2">
                                    <div>{{ $produit->nom }}</div>
                                    <div>{{ number_format($produit->prix_vente,2) }} DH</div>
                                    <button
                                        class="btn btn-success btn-sm btn-add-panier"
                                        data-id="{{ $produit->id }}"
                                        data-nom="{{ $produit->nom }}"
                                        data-prix="{{ $produit->prix_vente }}">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    let panier = [];

    function majPanier() {
        const body = document.getElementById('panier-body');
        let total = 0;
        body.innerHTML = '';

        if (panier.length === 0) {
            body.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Aucun produit</td></tr>`;
        } else {
            panier.forEach((item, i) => {
                total += item.prix * item.qte;
                body.innerHTML += `
                    <tr>
                        <td>${item.nom}</td>
                        <td>${item.qte}</td>
                        <td>${item.prix.toFixed(2)}</td>
                        <td>${(item.prix * item.qte).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm del" data-i="${i}">X</button>
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('total-payer').textContent = total.toFixed(2);
    }

    document.querySelectorAll('.btn-add-panier').forEach(btn => {
        btn.onclick = () => {
            let id = btn.dataset.id;
            let found = panier.find(p => p.id === id);
            if (found) {
                found.qte++;
            } else {
                panier.push({
                    id: id,
                    nom: btn.dataset.nom,
                    prix: parseFloat(btn.dataset.prix),
                    qte: 1
                });
            }
            majPanier();
        };
    });

    document.getElementById('panier-body').onclick = e => {
        if (e.target.classList.contains('del')) {
            panier.splice(e.target.dataset.i, 1);
            majPanier();
        }
    };

    function post(url) {
        if (panier.length === 0) {
            alert('Panier vide');
            return;
        }

        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                panier: panier
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('Erreur CSRF ou serveur');
            return res.json();
        })
        .then(() => {
            panier = [];
            majPanier();
            alert('Vente enregistrée');
        })
        .catch(err => {
            console.error(err);
            alert('Erreur lors de la vente');
        });
    }

    document.getElementById('btn-especes').onclick = () =>
        post("{{ route('pos.cash') }}");

    document.getElementById('btn-credit').onclick = () =>
        post("{{ route('pos.credit') }}");

});
</script>
@endpush
