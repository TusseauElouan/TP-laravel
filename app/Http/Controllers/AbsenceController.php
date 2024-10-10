<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Motif;
use App\Models\Absence;
use App\Mail\AbsenceMail;
use App\Mail\InfoGeneriqueMail;
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
     * Summary of GetMotifsCached
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected User $user;

    protected Motif $motif;
    protected User $concernedUser;
    public function GetMotifsCached()
    {
        $motifs = new Motif();

        return $motifs->getMotifsCache();
    }
    /**
     * Summary of initMail
     * @param \App\Models\Absence $absence
     * @return array<string, mixed>
     */
    public function initMail(Absence $absence){
        $user = Auth::user();
        $motif = Motif::find($absence['motif_id']);
        $concernedUser = User::find($absence['user_id_salarie']);

        $status = $absence->isValidated ? 'Validée':'En attente';

        return $details = [
            'Utilisateur' => $concernedUser->prenom.' '.$concernedUser->nom,
            'Motif' => $motif->libelle,
            'Date de début' => $absence->date_absence_debut,
            'Date de fin' => $absence->date_absence_fin,
            'Statut' => $status,
        ];
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

        $details = $this->initMail($absence);
        $user = Auth::user();

        Mail::to($user->email)->send(new InfoGeneriqueMail(
            __('absence.created_subject'),
            __('absence.created_content'),
            $details,
            $absence
        ));

        $admins = User::where('isAdmin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new InfoGeneriqueMail(
                __('absence.created_subject'),
                __("absence.created_content_admin", ['prenom' => $user->prenom, 'nom' => $user->nom]),
                $details,
                $absence,
                true
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence créée avec succès.');

    }

    /**
     * Summary of show
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
     * @return Factory|RedirectResponse|View
     */
    public function edit(Absence $absence)
    {
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

        $user = Auth::user();
        $concernedUser = User::find($validatedData['user_id_salarie']);

        $details = $this->initMail($absence);

        if ($user->isAn('admin') && $user->id !== $absence->user_id_salarie) {
            Mail::to($concernedUser->email)->send(new InfoGeneriqueMail(
                'Votre absence a été modifiée',
                'Votre absence a été mise à jour par un administrateur.',
                $details,
                $absence,
            ));
        }

        Mail::to($user->email)->send(new InfoGeneriqueMail(
            'Absence mise à jour',
            "L'absence a été mise à jour.",
            $details,
            $absence
        ));

        $admins = User::where('isAdmin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new InfoGeneriqueMail(
                'Une absence a été modifié',
                "Une absence a été modifié par {$user->prenom} {$user->nom}.",
                $details,
                $absence,
                true
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence modifiée avec succès.');
    }

    /**
     * Summary of destroy
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Absence $absence)
    {
        $absence->is_deleted = true;
        $absence->save();

        $details = $this->initMail($absence);
        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);
        Mail::to([$user->email, $concernedUser->email])->send(new InfoGeneriqueMail(
            'Absence supprimée',
            "L'absence a été supprimée.",
            $details,
            $absence
        ));
        return redirect()->route('absence.index')->with('success', 'Absence supprimé.');
    }

    /**
     * Summary of validate
     *
     * @return RedirectResponse
     */
    public function validate(Absence $absence)
    {
        $absence->isValidated = true;
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);

        $details = $this->initMail($absence);

        Mail::to([$user->email, $concernedUser->email])->send(new InfoGeneriqueMail(
            'Absence validée',
            "L'absence a été validée.",
            $details,
            $absence
        ));

        return redirect()->route('absence.index')->with('success', 'Absence validée avec succès.');
    }

    /**
     * Summary of restore
     *
     * @return RedirectResponse
     */
    public function restore(Absence $absence)
    {
        $absence->is_deleted = false;
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);
        $details = $this->initMail($absence);

        Mail::to([$user->email, $concernedUser->email])->send(new InfoGeneriqueMail(
            'Absence restorée',
            "L'absence a été restorée.",
            $details,
            $absence
        ));
        return redirect()->route('absence.index')->with('success', 'Absence restaurée.');
    }

    /**
     * Summary of showValidationPage
     *
     * @return Factory|View
     */
    public function showValidationPage(Absence $absence)
    {
        return view('absence.confirm', compact('absence'));
    }
}
