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
