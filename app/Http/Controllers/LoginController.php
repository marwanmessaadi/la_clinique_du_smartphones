<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur; // Importez votre modèle personnalisé
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche la page de connexion.
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Valide les informations de connexion en utilisant la table utilisateurs.
     */
    public function login_valid(Request $request)
    {
        // Validation des données avec messages personnalisés
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);
        

        // Récupérer l'utilisateur depuis la base de données
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($utilisateur && Hash::check($request->password, $utilisateur->password)) {
            // Authentifier manuellement l'utilisateur
            Auth::login($utilisateur);

            // Rediriger en fonction du rôle
            if ($utilisateur->role === 'client') {
                return redirect()->route('home')->with('success', 'Connexion réussie en tant que client !');
            }
            return redirect()->route('index')->with('success', 'Connexion réussie en tant qu\'administrateur !');
        }

        // Si la connexion échoue
        return redirect()->back()->withErrors(['email' => 'Les informations de connexion sont incorrectes.'])->withInput($request->only('email'));
    }
}
