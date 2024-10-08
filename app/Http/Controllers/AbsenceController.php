<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motif;
use App\Models\Absence;
use App\Mail\AbsenceMail;
use App\Mail\AbsenceModifiedMail;
use App\Mail\AbsenceValidateMail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use App\Mail\AbsenceModifiedMailAdmin;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\AbsenceCreateRequest;
use App\Http\Requests\AbsenceUpdateRequest;

class AbsenceController extends Controller
{
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
        $motif = Motif::all();
        $user = User::all();
        $absences = Absence::with(['user', 'motif'])->get();
        return view('absence.index', compact('absences'));
    }

    /**
     * Summary of create
     *
     * @return Factory|View|RedirectResponse
     */
    public function create()
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une absence.');
        }
        $users = User::all();
        $motifs = Motif::all();

        return view('absence.create', compact('users', 'motifs'));
    }

    /**
     * Summary of store
     *
     * @param AbsenceCreateRequest $validatedData
     *
     * @return RedirectResponse
     */
    public function store(AbsenceCreateRequest $validatedData)
    {
        $absence = new Absence();
        $absence->user_id_salarie = $validatedData['user_id_salarie'];
        $absence->motif_id = $validatedData['motif_id'];
        $absence->date_absence_debut = $validatedData['date_absence_debut'];
        $absence->date_absence_fin = $validatedData['date_absence_fin'];
        $absence->is_deleted = false;

        $absence->save();

        // Envoie de l'email après la création
        Mail::to('tusseauelouan@gmail.com')->send(new AbsenceMail($absence));
        return redirect()->route('absence.index')->with('success', 'Absence créée avec succès.');
    }

    /**
     * Summary of show
     *
     * @param \App\Models\Absence $absence
     *
     * @return Factory|View
     */
    public function show(Absence $absence)
    {
        return view('absence.index');
    }

    /**
     * Summary of edit
     *
     * @param \App\Models\Absence $absence
     *
     * @return Factory|RedirectResponse|View
     */
    public function edit(Absence $absence)
    {
        if ($absence->isValidated) {
            return redirect()->route('absence.index')->with('error', 'Cette absence est déjà validée.');
        }
        $users = User::all();
        $motifs = Motif::all();
        return view('absence.edit', compact(['absence', 'users','motifs']));
    }

    /**
     * Summary of update
     *
     * @param \App\Http\Requests\AbsenceUpdateRequest $request
     * @param \App\Models\Absence $absence
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AbsenceUpdateRequest $request, Absence $absence)
    {
        if ($absence->isValidated) {
            return redirect()->route('absence.index')->with('error', 'Cette absence est déjà validée.');
        }
        $validatedData = $request->validated();

        $absence->user_id_salarie = $validatedData['user_id_salarie'];
        $absence->motif_id = $validatedData['motif_id'];
        $absence->date_absence_debut = $validatedData['date_absence_debut'];
        $absence->date_absence_fin = $validatedData['date_absence_fin'];

        $absence->save();

        Mail::to($absence->user->email)->send(new AbsenceModifiedMail($absence));
        Mail::to($absence->user->email)->send(new AbsenceModifiedMailAdmin($absence));

        return redirect()->route('absence.index')->with('success', 'Absence modifiée avec succès.');
    }

    /**
     * Summary of destroy
     *
     * @param \App\Models\Absence $absence
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Absence $absence)
    {
        $absence->is_deleted = true;
        $absence->save();
        return redirect()->route('absence.index')->with('success', 'Absence supprimé.');
    }

    /**
     * Summary of validate
     * @param \App\Models\Absence $absence
     * @return RedirectResponse
     */
    public function validate(Absence $absence)
    {
        $absence->isValidated = true;
        $absence->save();

        Mail::to($absence->user->email)->send(new AbsenceValidateMail($absence));

        return redirect()->route('absence.index')->with('success', 'Absence validée avec succès.');
    }

    /**
     * Summary of restore
     * @param \App\Models\Absence $absence
     * @return RedirectResponse
     */
    public function restore(Absence $absence)
    {
        $absence->is_deleted = false;
        $absence->save();

        return redirect()->route('absence.index')->with('success', 'Absence restaurée.');
    }

    /**
     * Summary of showValidationPage
     * @param \App\Models\Absence $absence
     * @return Factory|View
     */
    public function showValidationPage(Absence $absence)
    {
        if (Auth::check() && Auth::user()->isA('admin')){
            return view('absence.confirm', compact('absence'));
        }
        return view('absence.index', compact('absences'))->with('success', 'Validation Success');
    }
}
