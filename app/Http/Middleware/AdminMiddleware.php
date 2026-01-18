<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Rôles disponibles dans l'application
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_VENDEUR = 'vendeur';
    const ROLE_CLIENT = 'client';
    const ROLE_GESTIONNAIRE = 'gestionnaire';

    /**
     * Routes accessibles aux clients
     */
    protected $clientRoutes = [
        'home',
        'profile',
        'client.*',
    ];

    /**
     * Vérifie si l'utilisateur connecté a un rôle autorisé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  Rôles autorisés (admin, vendeur, gestionnaire, etc.)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            // Sauvegarde l'URL demandée pour redirection après login
            if (!$request->expectsJson()) {
                session(['url.intended' => $request->url()]);
            }

            // Réponse JSON pour les requêtes API
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Non authentifié.',
                    'error' => 'Unauthenticated'
                ], 401);
            }

            return redirect()->route('login')
                ->with('warning', 'Veuillez vous connecter pour accéder à cette page.');
        }

        $user = Auth::user();

        // Vérifie que l'utilisateur a un rôle défini
        if (!isset($user->role) || empty($user->role)) {
            Log::warning('Utilisateur sans rôle détecté', [
                'user_id' => $user->id,
                'email' => $user->email ?? 'N/A',
                'ip' => $request->ip()
            ]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'Votre compte n\'a pas de rôle assigné. Contactez l\'administrateur.');
        }

        // Si aucun rôle spécifié, on autorise seulement admin par défaut
        if (empty($roles)) {
            $roles = [self::ROLE_ADMIN];
        }

        // Normalise les rôles (en minuscules)
        $roles = array_map('strtolower', $roles);
        $userRole = strtolower($user->role);

        // Vérifie si l'utilisateur a un des rôles autorisés
        if (in_array($userRole, $roles)) {
            // Log l'accès pour audit (optionnel, peut être désactivé en production)
            if (config('app.debug')) {
                Log::info('Accès autorisé', [
                    'user_id' => $user->id,
                    'role' => $userRole,
                    'route' => $request->route()->getName(),
                    'ip' => $request->ip()
                ]);
            }

            return $next($request);
        }

        // Si c'est un client qui tente d'accéder au backend
        if ($userRole === self::ROLE_CLIENT) {
            Log::warning('Client tentant d\'accéder au backend', [
                'user_id' => $user->id,
                'route' => $request->route()->getName(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Accès refusé.',
                    'error' => 'Forbidden'
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Vous n\'avez pas accès à cette section.');
        }

        // Pour tout autre cas, erreur 403
        Log::warning('Accès refusé - rôle non autorisé', [
            'user_id' => $user->id,
            'user_role' => $userRole,
            'required_roles' => $roles,
            'route' => $request->route()->getName(),
            'ip' => $request->ip()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Accès refusé. Rôle insuffisant.',
                'error' => 'Forbidden'
            ], 403);
        }

        abort(403, 'Accès interdit. Vous n\'avez pas les permissions nécessaires.');
    }

    /**
     * Vérifie si la route est accessible aux clients
     *
     * @param  string  $routeName
     * @return bool
     */
    protected function isClientRoute(string $routeName): bool
    {
        foreach ($this->clientRoutes as $pattern) {
            if (str_contains($pattern, '*')) {
                // Pattern avec wildcard
                $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';
                if (preg_match($regex, $routeName)) {
                    return true;
                }
            } elseif ($routeName === $pattern) {
                return true;
            }
        }

        return false;
    }
}