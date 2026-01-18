<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Affiche la page de connexion.
     */
    public function login()
    {
        // Redirige si déjà connecté
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'vendeur'])) {
                return redirect()->route('index');
            }
            return redirect()->route('home');
        }

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
            Auth::login($utilisateur, $request->has('remember'));
            
            // IMPORTANT : Régénérer la session pour éviter les attaques de fixation de session
            $request->session()->regenerate();

            // Log de connexion
            Log::info('Connexion réussie', [
                'user_id' => $utilisateur->id,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
                'ip' => $request->ip(),
            ]);

            // Redirection selon le rôle avec messages personnalisés
            switch ($utilisateur->role) {
                case 'client':
                    return redirect()->route('home')
                        ->with('success', 'Bienvenue ' . $utilisateur->prenom . ' ' . $utilisateur->nom . ' !');
                
                case 'admin':
                    return redirect()->route('index')
                        ->with('success', 'Connexion administrateur réussie ! Bienvenue ' . $utilisateur->prenom . ' !');
                
                case 'vendeur':
                    return redirect()->route('index')
                        ->with('success', 'Connexion vendeur réussie ! Bienvenue ' . $utilisateur->prenom . ' !');
                
                default:
                    // Rôle non reconnu : déconnecter et refuser l'accès
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    Log::warning('Tentative de connexion avec rôle non reconnu', [
                        'email' => $utilisateur->email,
                        'role' => $utilisateur->role,
                    ]);
                    
                    return redirect()->route('login')->withErrors([
                        'email' => 'Votre compte n\'a pas les permissions nécessaires.',
                    ]);
            }
        }

        // Si la connexion échoue
        Log::warning('Tentative de connexion échouée', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->back()
            ->withErrors(['email' => 'Les informations de connexion sont incorrectes.'])
            ->withInput($request->only('email'));
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Log de déconnexion
            if ($user) {
                Log::info('Déconnexion', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role,
                    'ip' => $request->ip(),
                ]);
            }

            // Déconnexion de l'utilisateur
            Auth::logout();

            // Invalider la session actuelle
            $request->session()->invalidate();

            // Régénérer le token CSRF pour la sécurité
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Vous avez été déconnecté avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la déconnexion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Force la déconnexion même en cas d'erreur
            Auth::logout();
            $request->session()->flush();
            
            return redirect()->route('login')
                ->with('warning', 'Déconnexion effectuée.');
        }
    }
}