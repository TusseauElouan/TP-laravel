<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenceCreateRequest;
use App\Http\Requests\AbsenceUpdateRequest;
use App\Mail\AbsenceMail;
use App\Mail\AbsenceModifiedMail;
use App\Mail\AbsenceModifiedMailAdmin;
use App\Mail\AbsenceValidateMail;
use App\Models\Absence;
use App\Models\Motif;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    /**
     * Summary of GetMotifsCached
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function GetMotifsCached()
    {
        $motifs = new Motif;

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
        $users = User::all();
        $motifs = $this->GetMotifsCached();

        return view('absence.create', compact('users', 'motifs'));
    }

    /**
     * Summary of store
     *
     *
     * @return RedirectResponse
     */
    public function store(AbsenceCreateRequest $validatedData)
    {
        $absence = new Absence;
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
     *
     * @return void
     */
    public function show(Absence $absence)
    {
        return redirect()->route('absence.index');
    }

    /**
     * Summary of edit
     *
     *
     * @return Factory|RedirectResponse|View
     */
    public function edit(Absence $absence)
    {
        if (Auth::check() && !Auth::user()->isAdmin){
            return redirect()->route('absence.index')->with('error',__('Not accessible to employee'));
        }
        if ($absence->isValidated) {
            return redirect()->route('absence.index')->with('error', 'Cette absence est déjà validée.');
        }
        $users = User::all();
        $motifs = $this->GetMotifsCached();

        return view('absence.edit', compact(['absence', 'users', 'motifs']));
    }

    /**
     * Summary of update
     *
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
     *
     *
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
     *
     *
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
     *
     *
     * @return Factory|View
     */
    public function showValidationPage(Absence $absence)
    {
        return view('absence.confirm', compact('absence'));
    }
}
