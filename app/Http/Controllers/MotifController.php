<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotifCreateRequest;
use App\Http\Requests\MotifUpdateRequest;
use App\Models\Absence;
use App\Models\Motif;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class MotifController extends Controller
{
    /**
     * Summary of GetMotifsCached
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Motif>
     */
    public function GetMotifsCached()
    {
        $motifs = new Motif();

        return $motifs->getMotifsCache();
    }

    /**
     * Summary of index
     *
     * @return Factory|RedirectResponse|View
     */
    public function index()
    {
        $motifs = $this->GetMotifsCached();

        return view('motif.index', compact('motifs'));
    }

    /**
     * Summary of create
     *
     * @return Factory|View|RedirectResponse
     */
    public function create()
    {
        return view('motif.create');
    }

    /**
     * Summary of store
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MotifCreateRequest $request)
    {
        $validatedData = $request->validated();

        $motif = new Motif();
        $motif->libelle = is_string($validatedData['libelle']) ? $validatedData['libelle'] : '';
        $motif->is_accessible_salarie = isset($validatedData['is_accessible_salarie']) ? (bool) $validatedData['is_accessible_salarie'] : false;
        $motif->save();

        Cache::forget('motifs');

        return redirect()->route('motif.index')->with('success', 'Motif créé avec succès.');
    }

    /**
     * Summary of show
     *
     * @return mixed
     */
    public function show(Motif $motif)
    {
        $motifs = $this->GetMotifsCached();

        return redirect()->to(route('motif.index'));
    }

    /**
     * Summary of edit
     *
     * @return Factory|RedirectResponse|View
     */
    public function edit(Motif $motif)
    {
        return view('motif.edit', compact('motif'));
    }

    /**
     * Summary of update
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MotifUpdateRequest $request, Motif $motif)
    {
        $validatedData = $request->validated();

        $motif->libelle = is_string($validatedData['libelle']) ? $validatedData['libelle'] : '';
        $motif->is_accessible_salarie = isset($validatedData['is_accessible_salarie']) ? (bool) $validatedData['is_accessible_salarie'] : false;
        $motif->save();

        Cache::forget('motifs');

        return redirect()->route('motif.index')->with('success', 'Motif modifié avec succès.');
    }

    /**
     * Summary of destroy
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Motif $motif)
    {
        $nb = Absence::where('motif_id', $motif->id)->count();

        if ($nb === 0) {
            $motif->delete();
            Cache::forget('motifs');

            return redirect()->route('motif.index')->with('success', 'Motif supprimé.');
        }

        return redirect()->route('motif.index')->with('error', "Ce motif est utilisé dans {$nb} absence(s).");
    }

    public function info()
    {
        return view('motif.info');
    }
}
