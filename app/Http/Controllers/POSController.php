<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produits;
use App\Models\Vente;
use App\Models\Utilisateur;
use App\Models\Reparation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class POSController extends Controller
{
    /**
     * Page principale POS - Redirige vers vente
     */
    public function index(Request $request)
    {
        return redirect()->route('pos.vente');
    }

    /**
     * Page POS Vente
     */
    public function venteIndex(Request $request)
    {
        $query = Produits::where('type', 'achat')
            ->where('quantite', '>', 0)
            ->with(['categorie', 'fournisseur']);
            
        if ($request->filled('search_produit')) {
            $search = $request->input('search_produit');
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }
        
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        $produits = $query->orderBy('nom')->get();
        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $clients = Utilisateur::where('role', 'client')->get();

        return view('pos.vente', compact('produits', 'categories', 'clients'));
    }

    /**
     * Page POS Réparation
     */
    public function reparationIndex(Request $request)
    {
        $clients = Utilisateur::where('role', 'client')->get();
        $reparationsQuery = Reparation::orderByDesc('created_at');

        if ($request->filled('search_reparation')) {
            $search = $request->input('search_reparation');
            $reparationsQuery->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('produit', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        if ($request->filled('etat_filter')) {
            $reparationsQuery->where('etat', $request->etat_filter);
        }

        $reparations = $reparationsQuery->paginate(15);

        return view('pos.reparation', compact('reparations', 'clients'));
    }

    /**
     * Supprimer réparation
     */
    public function deleteRepair($id)
    {
        try {
            $reparation = Reparation::findOrFail($id);
            $reparation->delete();
            
            return redirect()->route('pos.reparation')
                ->with('success', 'Réparation supprimée avec succès.');
                
        } catch (Exception $e) {
            Log::error('Erreur suppression réparation POS: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Créer / modifier réparation POS
     */
public function storeRepair(Request $request)
{
    // DEBUG: Log la requête
    Log::info('storeRepair appelé', [
        'request_data' => $request->all(),
        'method' => $request->method(),
        'has_id' => $request->has('id')
    ]);

    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'prix' => 'required|numeric|min:0',
        'produit' => 'required|string|max:255',
        'client_id' => 'nullable|exists:utilisateurs,id',
        'notes' => 'nullable|string|max:500',
        'etat' => 'required|in:en_cours,terminee,annulee',
    ]);

    try {
        if ($request->filled('id')) {
            Log::info('Mise à jour réparation ID: ' . $request->id);
            
            // Mise à jour
            $reparation = Reparation::findOrFail($request->id);
            $reparation->update($validated);
            
            Log::info('Réparation mise à jour: ' . $reparation->id);
            
            return redirect()->route('pos.reparation')
                ->with('success', 'Réparation mise à jour avec succès.');
                
        } else {
            Log::info('Création nouvelle réparation');
            
            // Création avec code unique
            $latest = Reparation::latest()->first();
            $number = $latest && $latest->code 
                ? intval(substr($latest->code, -6)) + 1 
                : 1;
            $validated['code'] = 'REP' . date('Y') . str_pad($number, 6, '0', STR_PAD_LEFT);
            $validated['date_reparation'] = now();
            
            $reparation = Reparation::create($validated);
            
            Log::info('Nouvelle réparation créée: ' . $reparation->id);
            
            return redirect()->route('pos.reparation')
                ->with('success', "Réparation créée avec succès. Code: {$reparation->code}")
                ->with('reparation_id', $reparation->id);
        }

    } catch (Exception $e) {
        Log::error('Erreur enregistrement réparation: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Imprimer ticket réparation avec code-barres
     */
    public function repairTicket($id)
    {
        try {
            $reparation = Reparation::findOrFail($id);

            // Génère le code-barres
            $barcode = $this->generateBarcodeHTML($reparation->code);

            $type = 'reparation';
            $data = [
                'code' => $reparation->code,
                'nom' => $reparation->nom,
                'produit' => $reparation->produit,
                'description' => $reparation->description,
                'prix' => $reparation->prix,
                'notes' => $reparation->notes,
                'barcode' => $barcode,
            ];

            return view('ticket', compact('type', 'data', 'reparation', 'barcode'));
            
        } catch (Exception $e) {
            Log::error('Erreur ticket réparation: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la génération du ticket.');
        }
    }

    /**
     * POS - Enregistrer vente
     */
    public function store(Request $request)
    {
        // Gestion du panier simple (depuis vente.blade.php basique)
        if ($request->has('panier')) {
            return $this->storeFromBasicCart($request);
        }

        // Gestion du panier avancé (depuis vente.blade.php moderne)
        $validated = $request->validate([
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
            'client_id' => 'nullable|exists:utilisateurs,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $total = 0;
        $ventes = [];
        $clientId = $validated['client_id'] ?? null;

        DB::transaction(function () use ($validated, &$total, &$ventes, $clientId) {
            foreach ($validated['produits'] as $item) {
                $produit = Produits::findOrFail($item['id']);
                
                if ($produit->quantite < $item['quantite']) {
                    throw new Exception("Stock insuffisant pour {$produit->nom}");
                }
                
                $prixTotal = $item['prix_unitaire'] * $item['quantite'];
                $total += $prixTotal;

                $vente = Vente::create([
                    'numero_vente' => Vente::generateNumeroVente(),
                    'produit_id' => $item['id'],
                    'utilisateur_id' => $clientId,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'prix_total' => $prixTotal,
                    'notes' => $validated['notes'] ?? null,
                    'date_vente' => now(),
                    'statut' => 'finalisee',
                ]);

                $produit->decrement('quantite', $item['quantite']);
                
                if ($produit->quantite <= 0) {
                    $produit->etat = 'vendu';
                    $produit->save();
                }
                
                $ventes[] = $vente;
            }
        });

        $client = $clientId ? Utilisateur::find($clientId) : null;
        $numeroVente = $ventes[0]->numero_vente ?? 'N/A';
        
        // Génère le code-barres pour la vente
        $barcode = $this->generateBarcodeHTML($numeroVente);
        
        $ticketData = [
            'id' => $numeroVente,
            'client' => $client ? $client->prenom . ' ' . $client->nom : 'Anonyme',
            'produits' => array_map(function($v) {
                return [
                    'nom' => $v->produit->nom,
                    'quantite' => $v->quantite,
                    'prix_unitaire' => $v->prix_unitaire
                ];
            }, $ventes),
            'total' => $total,
            'barcode' => $barcode,
        ];

        return view('ticket', [
            'type' => 'vente',
            'data' => $ticketData,
            'barcode' => $barcode
        ]);
    }

    /**
     * Gestion du panier basique (simple)
     */
    private function storeFromBasicCart(Request $request)
    {
        try {
            $panier = $request->input('panier', []);
            
            if (empty($panier)) {
                return response()->json(['error' => 'Panier vide'], 400);
            }

            $total = 0;
            $ventes = [];
            $clientId = $request->input('client_id');

            DB::transaction(function () use ($panier, &$total, &$ventes, $clientId) {
                foreach ($panier as $item) {
                    $produit = Produits::findOrFail($item['id']);
                    
                    $quantite = $item['qte'] ?? $item['quantite'] ?? 1;
                    $prixUnitaire = $item['prix'] ?? $produit->prix_vente;
                    
                    if ($produit->quantite < $quantite) {
                        throw new Exception("Stock insuffisant pour {$produit->nom}");
                    }
                    
                    $prixTotal = $prixUnitaire * $quantite;
                    $total += $prixTotal;

                    $vente = Vente::create([
                        'numero_vente' => Vente::generateNumeroVente(),
                        'produit_id' => $item['id'],
                        'utilisateur_id' => $clientId,
                        'quantite' => $quantite,
                        'prix_unitaire' => $prixUnitaire,
                        'prix_total' => $prixTotal,
                        'date_vente' => now(),
                        'statut' => 'finalisee',
                    ]);

                    $produit->decrement('quantite', $quantite);
                    
                    if ($produit->quantite <= 0) {
                        $produit->etat = 'vendu';
                        $produit->save();
                    }
                    
                    $ventes[] = $vente;
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Vente enregistrée avec succès',
                'total' => $total
            ]);

        } catch (Exception $e) {
            Log::error('Erreur vente basique: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Génère un code-barres HTML avec SVG
     */
    private function generateBarcodeHTML($code)
    {
        if (empty($code)) {
            $code = '000000';
        }

        // Génère un code-barres SVG simple de type Code 128
        $barcodeSVG = $this->generateCode128SVG($code);
        
        return '
        <div style="text-align: center; margin: 10px 0; padding: 10px; background: white;">
            ' . $barcodeSVG . '
            <div style="font-family: monospace; font-size: 11px; margin-top: 5px; letter-spacing: 2px;">
                ' . htmlspecialchars($code) . '
            </div>
        </div>';
    }

    /**
     * Génère un code-barres SVG simple (Code 128)
     */
    private function generateCode128SVG($code)
    {
        // Pattern simple pour représenter le code
        $width = strlen($code) * 12;
        $height = 50;
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Génère des barres basées sur les caractères
        $x = 0;
        for ($i = 0; $i < strlen($code); $i++) {
            $charCode = ord($code[$i]);
            $barWidth = ($charCode % 3) + 2;
            
            // Alterne noir/blanc
            if ($i % 2 == 0) {
                $svg .= '<rect x="' . $x . '" y="0" width="' . $barWidth . '" height="' . $height . '" fill="black"/>';
            }
            
            $x += $barWidth + 1;
        }
        
        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * API pour générer un code-barres (si nécessaire)
     */
    public function generateBarcodeApi($code)
    {
        $barcode = $this->generateBarcodeHTML($code);
        return response()->json(['barcode' => $barcode]);
    }

    /**
     * Recherche réparation par code
     */
    public function searchRepairByCode(Request $request)
    {
        $code = $request->input('code');
        
        $reparation = Reparation::where('code', 'like', "%$code%")->first();
        
        if ($reparation) {
            return response()->json([
                'success' => true,
                'reparation' => $reparation
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Réparation non trouvée'
        ], 404);
    }
}