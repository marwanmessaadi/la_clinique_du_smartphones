<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Back-office La Clinique du Smartphone - Gestion des ventes">
    <meta name="author" content="La Clinique du Smartphone">

    <title>La Clinique du Smartphone - Gestion des Ventes</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('/back/css/sb-admin-2.min.css') }}?v={{ time() }}" rel="stylesheet">

    <!-- Custom styles for smartphone shop -->
    <style>
        .sidebar-brand {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
        }
        .bg-sidebar {
            background-color: #2c3e50 !important;
        }
        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-dark .nav-item.active .nav-link {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        /* Style pour les boutons d'action */
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Clinique Smartphone</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Gestion Commerciale
            </div>

            <!-- Nav Item - Ventes -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('produits.vente') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Gérer les Ventes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('produits.achat') }}">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Gérer les Achats</span>
                </a>
            </li>

            <!-- Nav Item - Achats -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('produits.achat') }}">
                    <i class="fas fa-fw fa-boxes"></i>
                    <span>Approvisionnements</span>
                </a>
            </li>

            <!-- Nav Item - Stock -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('produits.index') }}">
                    <i class="fas fa-fw fa-warehouse"></i>
                    <span>Gestion du Stock</span>
                </a>
            </li>

            <!-- Nav Item - Réparations -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reparation.index') }}">
                    <i class="fas fa-fw fa-tools"></i>
                    <span>Service Après-Vente</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Administration
            </div>

            <!-- Nav Item - Utilisateurs -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilisateurs" aria-expanded="true" aria-controls="collapseUtilisateurs">
                    <i class="fas fa-fw fa-Utilisateurs"></i>
                    <span>Utilisateurs</span>
                </a>
                <div id="collapseUtilisateurs" class="collapse" aria-labelledby="headingUtilisateurs" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gestion des utilisateurs:</h6>
                        <a class="collapse-item" href="{{ route('utilisateurs.index') }}">Liste des utilisateurs</a>
                        <a class="collapse-item" href="#">Rôles et permissions</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Clients -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('clients.index') }}">
                    <i class="fas fa-fw fa-user-tie"></i>
                    <span>Clients</span>
                </a>
            </li>

            <!-- Nav Item - Fournisseurs -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('fournisseurs.index') }}">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Fournisseurs</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher une vente..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Utilisateur Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="UtilisateurDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">admin</span>
                                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - Utilisateur Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="UtilisateurDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-Utilisateur fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Paramètres
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Gestion des Ventes</h1>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSaleModal">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvelle Vente
                        </button>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Ventes du mois</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">€40,000</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Ventes aujourd'hui</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">€2,150</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Commandes en attente</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Liste des Ventes</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix Unitaire</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ventes as $vente)
                                        <tr>
                                            <td>{{ $vente->id }}</td>
                                            <td>{{ $vente->date_vente->format('d/m/Y') }}</td>
                                            <td>{{ $vente->client->nom }}</td>
                                            <td>
                                                {{ $vente->produit->nom }}
                                                <button type="button" class="btn btn-link btn-sm p-0 ml-2" data-toggle="modal" data-bs-toggle="modal" data-target="#productDetailModal{{ $vente->produit->id }}" data-bs-target="#productDetailModal{{ $vente->produit->id }}">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                                <!-- Modal Détail Produit -->
                                                <div class="modal fade" id="productDetailModal{{ $vente->produit->id }}" tabindex="-1" role="dialog" aria-labelledby="productDetailModalLabel{{ $vente->produit->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="productDetailModalLabel{{ $vente->produit->id }}">Détails du produit</h5>
                                                                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <strong>Nom :</strong> {{ $vente->produit->nom }}<br>
                                                                <strong>Description :</strong> {{ $vente->produit->description ?? 'Aucune' }}<br>
                                                                <strong>Catégorie :</strong> {{ $vente->produit->categorie->nom ?? 'Non catégorisé' }}<br>
                                                                <strong>Fournisseur :</strong> {{ $vente->produit->fournisseur->nom ?? 'N/A' }}<br>
                                                                <strong>Stock :</strong> {{ $vente->produit->stock ?? $vente->produit->quantite ?? 'N/A' }}<br>
                                                                <strong>Prix unitaire :</strong> {{ $vente->produit->prix_vente ?? $vente->prix_unitaire }} €<br>
                                                                @if($vente->produit->image)
                                                                    <img src="{{ asset('storage/' . $vente->produit->image) }}" alt="Image produit" class="img-fluid mt-2" style="max-height:120px;">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $vente->quantite }}</td>
                                            <td>{{ $vente->prix_unitaire }} €</td>
                                            <td>{{ $vente->prix_total }} €</td>
                                            <td>
                                                @if($vente->statut == 'completé')
                                                <span class="badge badge-success">{{ $vente->statut }}</span>
                                                @elseif($vente->statut == 'en attente')
                                                <span class="badge badge-warning">{{ $vente->statut }}</span>
                                                @else
                                                <span class="badge badge-danger">{{ $vente->statut }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-action btn-sm" data-toggle="modal" data-target="#editSaleModal{{ $vente->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('blank', $vente->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-action btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Edit Sale Modal -->
                                        <div class="modal fade" id="editSaleModal{{ $vente->id }}" tabindex="-1" role="dialog" aria-labelledby="editSaleModalLabel{{ $vente->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editSaleModalLabel{{ $vente->id }}">Modifier la Vente</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('blank', $vente->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="client_id">Client</label>
                                                                <select class="form-control" id="client_id" name="client_id" required>
                                                                    @foreach($clients as $client)
                                                                    <option value="{{ $client->id }}" {{ $vente->client_id == $client->id ? 'selected' : '' }}>{{ $client->nom }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="produit_id">Produit</label>
                                                                <select class="form-control" id="produit_id" name="produit_id" required>
                                                                    @foreach($produits as $produit)
                                                                    <option value="{{ $produit->id }}" {{ $vente->produit_id == $produit->id ? 'selected' : '' }}>{{ $produit->nom }} (Stock: {{ $produit->stock }})</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="quantite">Quantité</label>
                                                                <input type="number" class="form-control" id="quantite" name="quantite" value="{{ $vente->quantite }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="prix_unitaire">Prix Unitaire (€)</label>
                                                                <input type="number" step="0.01" class="form-control" id="prix_unitaire" name="prix_unitaire" value="{{ $vente->prix_unitaire }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="statut">Statut</label>
                                                                <select class="form-control" id="statut" name="statut" required>
                                                                    <option value="completé" {{ $vente->statut == 'completé' ? 'selected' : '' }}>Completé</option>
                                                                    <option value="en attente" {{ $vente->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                                                                    <option value="annulé" {{ $vente->statut == 'annulé' ? 'selected' : '' }}>Annulé</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; La Clinique du Smartphone {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Prêt à partir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Sélectionnez "Déconnexion" ci-dessous si vous êtes prêt à terminer votre session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-primary" href="{{ route('login') }}">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Sale Modal -->
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSaleModalLabel">Nouvelle Vente</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('ventes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_client_id">Client</label>
                            <select class="form-control" id="new_client_id" name="client_id" required>
                                <option value="">Sélectionnez un client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_produit_id">Produit</label>
                            <select class="form-control" id="new_produit_id" name="produit_id" required>
                                <option value="">Sélectionnez un produit</option>
                                @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" data-stock="{{ $produit->stock }}">{{ $produit->nom }} (Stock: {{ $produit->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="new_quantite">Quantité</label>
                            <input type="number" class="form-control" id="new_quantite" name="quantite" min="1" required>
                            <small id="stockHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="form-group">
                            <label for="new_prix_unitaire">Prix Unitaire (€)</label>
                            <input type="number" step="0.01" class="form-control" id="new_prix_unitaire" name="prix_unitaire" required>
                        </div>
                        <div class="form-group">
                            <label for="new_statut">Statut</label>
                            <select class="form-control" id="new_statut" name="statut" required>
                                <option value="completé">Completé</option>
                                <option value="en attente">En attente</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}?v={{ time() }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}?v={{ time() }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}?v={{ time() }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}?v={{ time() }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}?v={{ time() }}"></script>

    <script>
        // Vérification du stock lors de la sélection d'un produit
        $('#new_produit_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var stock = selectedOption.data('stock');
            $('#stockHelp').text('Stock disponible: ' + stock);
            $('#new_quantite').attr('max', stock);
        });

        // Calcul du prix total lors de la modification de la quantité ou du prix unitaire
        $('#new_quantite, #new_prix_unitaire').on('input', function() {
            var quantite = parseFloat($('#new_quantite').val()) || 0;
            var prixUnitaire = parseFloat($('#new_prix_unitaire').val()) || 0;
            var total = quantite * prixUnitaire;
            $('#stockHelp').text('Stock disponible: ' + $('#new_produit_id').find('option:selected').data('stock') + ' | Total: ' + total.toFixed(2) + ' €');
        });

        $(document).ready(function() {
          $(document).on('click', '[data-toggle="modal"], [data-bs-toggle="modal"]', function(e) {
            var target = $(this).attr('data-target') || $(this).attr('data-bs-target');
            if (target) {
              $(target).modal('show');
              e.preventDefault();
            }
          });
        });
    </script>

</body>

</html>
