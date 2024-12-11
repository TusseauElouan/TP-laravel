<?php

namespace App\Http\Controllers;

use App\Models\JourFerie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class JourFerieController extends Controller
{
    /**
     * Affiche la liste des jours fériés
     */
    public function index(): View
    {
        $joursFeries = JourFerie::orderBy('date')->get();
        return view('joursferies.index', compact('joursFeries'));
    }

    /**
     * Crée un nouveau jour férié
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'boolean'
        ]);

        $jourFerie = JourFerie::create($validated);

        return response()->json($jourFerie);
    }

    /**
     * Récupère les informations d'un jour férié spécifique
     */
    public function show(JourFerie $jourFerie): JsonResponse
    {
        return response()->json($jourFerie);
    }

    /**
     * Met à jour un jour férié
     */
    public function update(Request $request, JourFerie $jourFerie): JsonResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'boolean'
        ]);

        $jourFerie->update($validated);

        return response()->json($jourFerie);
    }

    /**
     * Supprime un jour férié
     */
    public function destroy(JourFerie $jourFerie): JsonResponse
    {
        $jourFerie->delete();
        return response()->json(['message' => 'Jour férié supprimé avec succès']);
    }

    /**
     * Récupère tous les jours fériés
     */
    public function getAll(): JsonResponse
    {
        $joursFeries = JourFerie::all();
        return response()->json($joursFeries);
    }
}
