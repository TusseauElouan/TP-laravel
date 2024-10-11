<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Summary of index
     *
     * @return Factory|View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $users = User::all();
        $absences = Absence::all();

        return view('user.index', compact(['users', 'absences']));
    }

    /**
     * Summary of create
     *
     * @return mixed|RedirectResponse
     */
    public function create()
    {
        return redirect()->to(route('user.index'));
    }

    /**
     * Summary of show
     *
     * @return Factory|RedirectResponse|View
     */
    public function show(User $user)
    {
        if (Auth::check() && Auth::user() && ! Auth::user()->isAdmin) {
            return redirect()->route('user.index')->with('error', __('Not accessible to employee'));
        }
        $absences = Absence::with('motif')->where('user_id_salarie', $user->id)->get();

        return view('user.show', compact('user', 'absences'));
    }

    /**
     * Summary of edit
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $user->nom = is_string($request->nom) ? $request->nom : '';
        $user->prenom = is_string($request->prenom) ? $request->prenom : '';
        $user->email = is_string($request->email) ? $request->email : '';

        $user->save();

        return redirect()->route('user.index')->with('success', 'Utilisateur modifié avec succès.');
    }
}
