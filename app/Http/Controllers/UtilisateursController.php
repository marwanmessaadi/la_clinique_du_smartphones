<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Utilisateur;

class UtilisateursController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Utilisateur::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $utilisateurs = $query->paginate(10);

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retourner la vue de création d'utilisateur
        return view('utilisateurs.create');
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
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|string|',
            'telephone' => 'nullable|string|max:10',
            'role' => 'required|string',
        ]);

        // Création de l'utilisateur
        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hachage du mot de passe
            'telephone' => $request->telephone,
            'role' => $request->role,
        ]);
        
        // Redirection avec un message de succès
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('utilisateurs.show', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Récupérer l'utilisateur par son ID
        $utilisateur = Utilisateur::findOrFail($id);

        // Retourner la vue avec l'utilisateur
        return view('utilisateurs.edit', ['utilisateur' => $utilisateur]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email,' . $id,
            'password' => 'nullable|string|min:6',
            'telephone' => 'nullable|string|max:10',
            'role' => 'required|string|in:admin,client',
        ]);

        // Récupérer l'utilisateur
        $utilisateur = Utilisateur::findOrFail($id);

        // Mettre à jour les données
        $utilisateur->nom = $request->nom;
        $utilisateur->prenom = $request->prenom;
        $utilisateur->email = $request->email;
        $utilisateur->telephone = $request->telephone;
        $utilisateur->role = $request->role;

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $utilisateur->password = bcrypt($request->password);
        }

        $utilisateur->save();

        // Redirection avec un message de succès
        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Suppression de l'utilisateur
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        return redirect()->route('utilisateurs.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
