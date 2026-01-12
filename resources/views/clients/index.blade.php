@extends('layouts.app')
@section('title', 'Liste des Clients')
@section('content')
<style>
    .dashboard-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.25rem;
        color: #2d3748;
        font-weight: 600;
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.5;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        color: #ffffff;
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        border: 1px solid transparent;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #138496 0%, #17a2b8 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
    }

    .btn-warning {
        color: #ffffff;
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        border: 1px solid transparent;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #e8680f 0%, #e0a800 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(253, 126, 20, 0.3);
    }

    .btn-danger {
        color: #ffffff;
        background-color: #dc2626;
        border: 1px solid transparent;
    }

    .btn-danger:hover {
        background-color: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    }

    .table-container {
        overflow-x: auto;
        padding: 1rem;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table th {
        background-color: #f8fafc;
        padding: 1rem;
        font-weight: 600;
        text-align: left;
        color: #4a5568;
        border-bottom: 2px solid #e2e8f0;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #4a5568;
    }

    .table tr:hover {
        background-color: #f8fafc;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .badge-client {
        background-color: #10b981;
        color: #ffffff;
    }

    .actions {
        display: flex;
        gap: 0.5rem;
    }

    .actions form {
        margin: 0;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        margin-top: 1rem;
    }

    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-link:hover {
        background-color: #f7fafc;
    }

    .page-link.active {
        background-color: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }
</style>

<div class="dashboard-container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title text-white">Liste des Clients</h2>
            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Ajouter un client
            </a>
        </div>

        <!-- Search Form -->
        <div class="p-3 border-bottom">
            <form method="GET" class="d-flex">
                <input type="text" class="form-control me-2" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom ou email...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Rechercher
                </button>
            </form>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->prenom }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->telephone }}</td>
                            <td>
                                <span class="badge badge-client">
                                    {{ $client->role }}
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="pagination">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
