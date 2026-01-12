<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Affiche le formulaire d'enregistrement.
     */
    public function create()
    {
        return view('register');
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'nullable|string|max:10',
            'role' => 'string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Création de l'utilisateur
        Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
             'role' => $request->role,
            'password' => Hash::make($request->password), // Hachage du mot de passe
        ]);

        // Redirection après enregistrement
        return redirect()->route('login')->with('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show Utilisateur details
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
