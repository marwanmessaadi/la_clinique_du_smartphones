<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;


class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Utilisateur::where('role', 'client');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $clients = $query->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retourner la vue de création d'utilisateur
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:Utilisateurs,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'telephone' => 'nullable|string|max:10',
            'role' => 'required|string',
        ]);

        // Création de l'utilisateur
        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hachage du mot de passe
            'telephone' => $request->telephone,
            'role' => $request->role,
        ]);

        // Redirection après enregistrement
        return redirect()->route('clients.index')->with('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Afficher les détails de l'utilisateur avec ses relations
        $utilisateur = Utilisateur::with(['ventes.produit'])->findOrFail($id);
        return view('clients.show', ['utilisateur' => $utilisateur]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Afficher le formulaire d'édition de l'utilisateur
        $utilisateur = Utilisateur::findOrFail($id);
        return view('clients.edit', ['utilisateur' => $utilisateur]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:Utilisateurs,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|string|min:6',
            'telephone' => 'nullable|string|max:10',
            'role' => 'required|string',
        ]);

        // Récupérer l'utilisateur par son ID
        $utilisateur = Utilisateur::findOrFail($id);

        // Mettre à jour les informations de l'utilisateur
        $utilisateur->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $utilisateur->password, 
            'telephone' => $request->telephone,
            'role' => $request->role,
        ]);

        // Redirection après mise à jour
        return redirect()->route('clients.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer l'utilisateur par son ID
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        // Redirection après suppression
        return redirect()->route('clients.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}