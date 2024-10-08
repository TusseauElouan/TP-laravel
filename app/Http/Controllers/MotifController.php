<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotifCreateRequest;
use App\Http\Requests\MotifUpdateRequest;
use App\Models\Absence;
use App\Models\Motif;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class MotifController extends Controller
{
    /**
     * Summary of middleware
     * @return Middleware[]
     */
    public static function middleware()
    {
        return [
            new Middleware('admin', except: ['index', 'show']),
        ];
    }
    /**
     * Summary of index
     *
     * @return Factory|RedirectResponse|View
     */
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter.');
        }
        $motifs = Motif::all();
        return view('motif.index', compact('motifs'));
    }
    /**
     * Summary of create
     *
     * @return Factory|View|RedirectResponse
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->isA('admin')) {
            return view('motif.create');
        }

        return redirect()->route('accueil')->with('error', 'Accès refusé');
    }
    /**
     * Summary of store
     *
     * @param \App\Http\Requests\MotifCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MotifCreateRequest $request)
    {
        // Les données sont déjà validées par MotifRequest
        $validatedData = $request->validated();

        // Création du motif
        $motif = new Motif();
        $motif->libelle = $validatedData['libelle'];
        $motif->is_accessible_salarie = $validatedData['is_accessible_salarie'] ?? false;
        $motif->save();

        // Redirection avec message de succès
        return redirect()->route('motif.index')->with('success', 'Motif créé avec succès.');
    }

    /**
     * Summary of show
     *
     * @param \App\Models\Motif $motif
     *
     * @return mixed
     */
    public function show(Motif $motif)
    {
        return view('motif.index');
    }

    /**
     * Summary of edit
     *
     * @param Motif $motif
     *
     * @return Factory|RedirectResponse|View
     */
    public function edit(Motif $motif)
    {
        if (Auth::check() && Auth::user()->isA('admin')) {
            return view('motif.edit', compact('motif'));
        }

        return redirect()->route('motif.index')->with('error', 'Accès refusé');
    }

    /**
     * Summary of update
     *
     * @param \App\Http\Requests\MotifUpdateRequest $request
     * @param \App\Models\Motif $motif
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MotifUpdateRequest $request, Motif $motif)
    {
        // Les données sont déjà validées par MotifRequest
        $validatedData = $request->validated();

        // Mise à jour du motif
        $motif->libelle = $validatedData['libelle'];
        $motif->is_accessible_salarie = $validatedData['is_accessible_salarie'] ?? false;
        $motif->save();

        // Redirection avec message de succès
        return redirect()->route('motif.index')->with('success', 'Motif modifié avec succès.');
    }

    /**
     * Summary of destroy
     *
     * @param \App\Models\Motif $motif
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Motif $motif)
    {
        $nb = Absence::where('motif_id', $motif->id)->count();

        if ($nb === 0) {
            $motif->delete();
            return redirect()->route('motif.index')->with('success', 'Motif supprimé.');
        }
        return redirect()->route('motif.index')->with('error', "Ce motif est utilisé dans {$nb} absence(s).");
    }
}
