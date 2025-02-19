<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Bouncer;

class RoleController extends Controller
{
    public function index()
    {
        if (auth()->user()->isA('admin')) {
            $roles = Bouncer::role()->all(); // Récupère tous les rôles
            return view('role.index', compact('roles'));
        }
    }

    public function create()
    {
        if (auth()->user()->isA('admin')) {
            $role = new Role();
            $abilities = Bouncer::ability()->all(); // Récupère toutes les permissions
            $roleAbilities = []; // Aucune capacité attribuée pour un rôle en création

            return view('role.create', compact('role', 'abilities', 'roleAbilities'));
        }
    }

    public function store(Request $request)
    {
        if (auth()->user()->isA('admin')) {
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
        if (!auth()->user()->isA('admin')) {
            abort(403);
        }

        $abilities = Bouncer::ability()->get();
        $roleAbilities = $role->abilities()->pluck('name')->toArray();

        return view('role.edit', compact('role', 'abilities', 'roleAbilities'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->isA('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'abilities' => 'nullable|array',
            'abilities.*' => 'exists:abilities,name',
        ]);

        try {
            \DB::beginTransaction();

            // Mise à jour du rôle
            $role->update([
                'name' => $request->name,
                'title' => $request->title ?? ucfirst($request->name),
            ]);

            // Récupérer les capacités actuelles
            $currentAbilities = $role->abilities()->pluck('name')->toArray();

            // Nouvelles capacités sélectionnées
            $selectedAbilities = $request->abilities ?? [];

            // Retirer les permissions décochées via Bouncer
            $abilitiesToRemove = array_diff($currentAbilities, $selectedAbilities);
            foreach ($abilitiesToRemove as $abilityName) {
                $role->disallow($abilityName);
            }

            // Ajouter les nouvelles permissions via Bouncer
            $abilitiesToAdd = array_diff($selectedAbilities, $currentAbilities);
            foreach ($abilitiesToAdd as $abilityName) {
                $role->allow($abilityName);
            }

            // Mettre à jour la table pivot
            $abilityIds = Bouncer::ability()
                ->whereIn('name', $selectedAbilities)
                ->pluck('id')
                ->toArray();
            $role->abilities()->sync($abilityIds);

            Bouncer::refresh();

            \DB::commit();

            return redirect()
                ->route('role.index')
                ->with('success', 'Rôle mis à jour avec succès.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du rôle.')
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        if (auth()->user()->isA('admin')) {
            // Suppression du rôle
            $role->delete();

            return redirect()->route('role.index')->with('success', 'Rôle supprimé avec succès.');
        }
    }
}
