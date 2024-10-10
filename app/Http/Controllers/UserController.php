<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;

class UserController extends Controller
{
    /**
     * Summary of index
     *
     * @return Factory|View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter.');
        }
        $users = User::all();
        $absences = Absence::all();

        return view('user.index', compact(['users', 'absences']));
    }

    /**
     * Summary of create
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Summary of store
     *
     *
     * @return void
     */
    public function store(Request $request) {}

    /**
     * Summary of show
     * @param int $id
     * @return Factory|View|RedirectResponse
     */
    public function show(int $id)
    {
        if (Auth::check() && !Auth::user()->isAdmin)
        {
            return redirect()->route('user.index')->with('error',__('Not accessible to employee'));
        }
        $user = User::findOrFail($id);
        $absences = Absence::with('motif')->where('user_id_salarie', $user->id)->get();

        return view('user.show', compact('user', 'absences'));
    }

    /**
     * Summary of edit
     *
     *
     * @return Factory|View
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Summary of update
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('user.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Summary of destroy
     *
     *
     * @return void
     */
    public function destroy(User $motif) {}
}
