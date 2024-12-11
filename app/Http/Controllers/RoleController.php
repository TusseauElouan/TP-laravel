<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Bouncer;

class RoleController extends Controller
{
    public function index()
    {
        if (auth()->user()->isA('admin'))
        {
            $roles = Bouncer::role()->all(); // Récupère tous les rôles
            return view('role.index', compact('roles'));
        }
    }

    public function create()
    {
        if (auth()->user()->isA('admin'))
        {
            $role = new Role();
            $abilities = Bouncer::ability()->all(); // Récupère toutes les permissions
            $roleAbilities = []; // Aucune capacité attribuée pour un rôle en création

            return view('role.create', compact('role', 'abilities', 'roleAbilities'));
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()->isA('admin'))
        {
            $request->validate([
                'name' => 'required|string|unique:roles',
                'abilities' => 'nullable|array', // Les permissions sélectionnées
                'abilities.*' => 'exists:abilities,name', // Vérifie que chaque capacité existe
            ]);

            // Création du rôle
            $role = Bouncer::role()->create([
                'name' => $request->name,
                'title' => $request->title ?? ucfirst($request->name),
            ]);

            // Vérifie et attribue les capacités au rôle
            if ($request->has('abilities')) {
                Bouncer::sync($role)->abilities($request->abilities);
            }

            return redirect()->route('role.index')->with('success', 'Rôle créé avec succès.');
        }
    }

    public function edit(Role $role)
    {
        if (auth()->user()->isA('admin'))
        {
            $abilities = Bouncer::ability()->all(); // Récupère toutes les permissions
            $roleAbilities = Bouncer::role()->find($role->id)->abilities()->pluck('name')->toArray();

            return view('role.edit', compact('role', 'abilities', 'roleAbilities'));
        }
    }

    public function update(Request $request, Role $role)
    {
        if (auth()->user()->isA('admin'))
        {
            $request->validate([
                'name' => 'required|string|unique:roles,name,' . $role->id,
                'abilities' => 'nullable|array', // Les permissions sélectionnées
                'abilities.*' => 'exists:abilities,name', // Vérifie que chaque capacité existe
            ]);

            // Mise à jour du rôle
            $role->update([
                'name' => $request->name,
                'title' => $request->title ?? ucfirst($request->name),
            ]);

            // Récupère les capacités actuelles du rôle
            $currentAbilities = $role->abilities()->pluck('name')->toArray();

            // Capacités envoyées par le formulaire
            $selectedAbilities = $request->abilities ?? [];

            // Identifie les capacités à ajouter
            $abilitiesToAdd = array_diff($selectedAbilities, $currentAbilities);
            // Identifie les capacités à supprimer
            $abilitiesToRemove = array_diff($currentAbilities, $selectedAbilities);

            // Supprime les capacités décochées
            foreach ($abilitiesToRemove as $abilityName) {
                // Récupère l'ID de la capacité à supprimer
                $ability = Bouncer::ability()->where('name', $abilityName)->first();
                if ($ability) {
                    // Retirer la capacité du rôle et supprimer de la table pivot
                    $role->disallow($abilityName);
                    // Supprimer la relation dans la table pivot en utilisant `detach`
                    $role->abilities()->detach($ability->id); // Supprime la capacité de la relation
                }
            }

            // Ajoute les nouvelles capacités
            foreach ($abilitiesToAdd as $abilityName) {
                // Récupère l'ID de la capacité
                $ability = Bouncer::ability()->where('name', $abilityName)->first();
                if ($ability && !$role->abilities()->where('ability_id', $ability->id)->exists()) {
                    $role->allow($abilityName); // Ajouter la capacité au rôle
                    $role->abilities()->attach(
                        $ability->id, ['entity_type' => 'roles']
                    );
                }
            }

            return redirect()->route('role.index')->with('success', 'Rôle mis à jour avec succès.');
        }
    }

    public function destroy(Role $role)
    {
        if (auth()->user()->isA('admin'))
        {
            // Suppression du rôle
            $role->delete();

            return redirect()->route('role.index')->with('success', 'Rôle supprimé avec succès.');
        }
    }
}
