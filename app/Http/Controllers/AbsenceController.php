<?php

namespace App\Http\Controllers;

use App\Http\Requests\AbsenceCreateRequest;
use App\Http\Requests\AbsenceUpdateRequest;
use App\Mail\InfoGeneriqueMail;
use App\Models\Absence;
use App\Models\Motif;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AbsenceController extends Controller
{
    protected User $user;

    protected Motif $motif;

    protected User $concernedUser;

    /**
     * Get cached motifs.
     *
     * @return Collection<int, Motif>
     */
    public function GetMotifsCached(): Collection
    {
        $motifs = new Motif();

        return $motifs->getMotifsCache();
    }

    /**
     * Initialize mail details.
     *
     * @return array<string, mixed>
     */
    public function initMail(Absence $absence): array
    {
        $user = Auth::user();
        $motif = Motif::find($absence->motif_id);
        $concernedUser = User::find($absence->user_id_salarie);

        $status = $absence->isValidated ? 'Validée' : 'En attente';

        return [
            'Utilisateur' => $concernedUser ? $concernedUser->prenom.' '.$concernedUser->nom : 'Unknown',
            'Motif' => $motif ? $motif->libelle : 'Unknown',
            'Date de début' => $absence->date_absence_debut,
            'Date de fin' => $absence->date_absence_fin,
            'Statut' => $status,
        ];
    }

    /**
     * Display a listing of the absences.
     */
    public function index(): Factory|View
    {
        $absences = Absence::with(['user', 'motif'])->get();

        return view('absence.index', compact('absences'));
    }

    /**
     * Show the form for creating a new absence.
     */
    public function create(): Factory|View
    {
        $users = User::all();
        $motifs = $this->GetMotifsCached();

        return view('absence.create', compact('users', 'motifs'));
    }

    /**
     * Store a newly created absence in storage.
     */
    public function store(AbsenceCreateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $userIdSalarie = isset($validatedData['user_id_salarie']) && is_numeric($validatedData['user_id_salarie'])
        ? (int) $validatedData['user_id_salarie']
        : 1;

        $motifId = isset($validatedData['motif_id']) && is_numeric($validatedData['motif_id'])
        ? (int) $validatedData['motif_id']
        : 1;

        $dateAbsenceDebut = isset($validatedData['date_absence_debut']) && is_string($validatedData['date_absence_debut'])
        ? (string) $validatedData['date_absence_debut']
        : '';

        $dateAbsenceFin = isset($validatedData['date_absence_fin']) && is_string($validatedData['date_absence_fin'])
        ? (string) $validatedData['date_absence_fin']
        : '';

        $commentaire = isset($validatedData['commentaire']) && is_string($validatedData['commentaire'])
        ? (string) $validatedData['commentaire']
        : '';

        $absence = new Absence();

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');

            // Validation supplémentaire du fichier
            if (!in_array($file->getClientOriginalExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                return redirect()->back()->with('error', 'Format de fichier non autorisé. Utilisez PDF, JPG ou PNG.');
            }

            if ($file->getSize() > 5242880) { // 5 Mo en octets
                return redirect()->back()->with('error', 'Le fichier ne doit pas dépasser 5 Mo.');
            }

            // Générer un nom unique pour le fichier
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Stocker dans le dossier private/justificatifs
            $path = $file->storeAs('justificatifs', $fileName, 'private');
            $absence->justificatif_path = $path;
        }

        $absence->user_id_salarie = $userIdSalarie;
        $absence->motif_id = $motifId;
        $absence->date_absence_debut = $dateAbsenceDebut;
        $absence->date_absence_fin = $dateAbsenceFin;
        $absence->commentaire = $commentaire;
        $absence->isRefused = false;
        $absence->is_deleted = 0;
        $absence->save();

        $details = $this->initMail($absence);
        $user = Auth::user();

        if ($user) {
            Mail::to($user->email)->send(new InfoGeneriqueMail(
                'Nouvelle absence créée',
                'Une nouvelle absence a été créée.',
                $details,
                $absence
            ));
        }

        $admins = User::where('isAdmin', true)->get();
        foreach ($admins as $admin) {
            if ($admin instanceof User) {
                Mail::to($admin->email)->send(new InfoGeneriqueMail(
                    'Nouvelle absence créée',
                    $user ? "Une nouvelle absence a été créée par {$user->prenom} {$user->nom}." : 'Une nouvelle absence a été créée.',
                    $details,
                    $absence,
                    true
                ));
            }
        }

        return redirect()->route('absence.index')->with('success', 'Absence créée avec succès.');
    }

    /**
     * Display the specified absence.
     */
    public function show(Absence $absence): RedirectResponse
    {
        return redirect()->route('absence.index');
    }

    /**
     * Show the form for editing the specified absence.
     */
    public function edit(Absence $absence): Factory|RedirectResponse|View
    {
        if ($absence->isValidated) {
            return redirect()->route('absence.index')->with('error', 'Cette absence est déjà validée.');
        }
        $users = User::all();
        $motifs = $this->GetMotifsCached();

        return view('absence.edit', compact(['absence', 'users', 'motifs']));
    }

    /**
     * Update the specified absence in storage.
     */
    public function update(AbsenceUpdateRequest $request, Absence $absence): RedirectResponse
    {
        if ($absence->isValidated) {
            return redirect()->route('absence.index')->with('error', 'Cette absence est déjà validée.');
        }

        $validatedData = $request->validated();

        $userIdSalarie = isset($validatedData['user_id_salarie']) && is_numeric($validatedData['user_id_salarie'])
        ? (int) $validatedData['user_id_salarie']
        : 1;

        $motifId = isset($validatedData['motif_id']) && is_numeric($validatedData['motif_id'])
        ? (int) $validatedData['motif_id']
        : 1;

        $dateAbsenceDebut = isset($validatedData['date_absence_debut']) && is_string($validatedData['date_absence_debut'])
        ? (string) $validatedData['date_absence_debut']
        : '';

        $dateAbsenceFin = isset($validatedData['date_absence_fin']) && is_string($validatedData['date_absence_fin'])
        ? (string) $validatedData['date_absence_fin']
        : '';


        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');

        $commentaire = isset($validatedData['commentaire']) && is_string($validatedData['commentaire'])
        ? (string) $validatedData['commentaire']
        : '';

        $absence->user_id_salarie = $userIdSalarie;
        $absence->motif_id = $motifId;
        $absence->date_absence_debut = $dateAbsenceDebut;
        $absence->date_absence_fin = $dateAbsenceFin;
        $absence->commentaire = $commentaire;


            // Validation du fichier
            if (!in_array($file->getClientOriginalExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                return redirect()->back()->with('error', 'Format de fichier non autorisé. Utilisez PDF, JPG ou PNG.');
            }

            if ($file->getSize() > 5242880) {
                return redirect()->back()->with('error', 'Le fichier ne doit pas dépasser 5 Mo.');
            }

            // Si un ancien fichier existe, le supprimer
            if ($absence->justificatif_path) {
                Storage::disk('private')->delete($absence->justificatif_path);
            }

            // Stocker le nouveau fichier
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('justificatifs', $fileName, 'private');
            $absence->justificatif_path = $path;
        }

        // Enregistrez les autres champs
        $absence->user_id_salarie = $request->input('user_id_salarie');
        $absence->motif_id = $request->input('motif_id');
        $absence->date_absence_debut = $request->input('date_absence_debut');
        $absence->date_absence_fin = $request->input('date_absence_fin');

        // dd($absence);
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($validatedData['user_id_salarie']);

        $details = $this->initMail($absence);

        if ($user && $user->isAn('admin') && $user->id !== $absence->user_id_salarie && $concernedUser instanceof User) {
            Mail::to($concernedUser->email)->send(new InfoGeneriqueMail(
                'Votre absence a été modifiée',
                'Votre absence a été mise à jour par un administrateur.',
                $details,
                $absence,
            ));
        }

        if ($user) {
            Mail::to($user->email)->send(new InfoGeneriqueMail(
                'Absence mise à jour',
                "L'absence a été mise à jour.",
                $details,
                $absence
            ));
        }

        $admins = User::where('isAdmin', true)->get();
        foreach ($admins as $admin) {
            if ($admin instanceof User) {
                Mail::to($admin->email)->send(new InfoGeneriqueMail(
                    'Une absence a été modifiée',
                    $user ? "Une absence a été modifiée par {$user->prenom} {$user->nom}." : 'Une absence a été modifiée.',
                    $details,
                    $absence,
                    true
                ));
            }
        }

        return redirect()->route('absence.index')->with('success', 'Absence modifiée avec succès.');
    }

    /**
     * Remove the specified absence from storage.
     */
    public function destroy(Absence $absence): RedirectResponse
    {
        $absence->is_deleted = 1;
        $absence->save();

        $details = $this->initMail($absence);
        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);

        $recipients = array_filter([$user?->email, $concernedUser instanceof User ? $concernedUser->email : null]);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new InfoGeneriqueMail(
                'Absence supprimée',
                "L'absence a été supprimée.",
                $details,
                $absence
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence supprimé.');
    }

    /**
     * Validate the specified absence.
     */
    public function validate(Absence $absence): RedirectResponse
    {
        $absence->isValidated = true;
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);

        $details = $this->initMail($absence);

        $recipients = array_filter([$user?->email, $concernedUser instanceof User ? $concernedUser->email : null]);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new InfoGeneriqueMail(
                'Absence validée',
                "L'absence a été validée.",
                $details,
                $absence
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence validée avec succès.');
    }

    /**
     * Validate the specified absence.
     */
    public function refuse(Absence $absence): RedirectResponse
    {
        $absence->isRefused = true;
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);

        $details = $this->initMail($absence);

        $recipients = array_filter([$user?->email, $concernedUser instanceof User ? $concernedUser->email : null]);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new InfoGeneriqueMail(
                'Absence refusée',
                "L'absence a été refusée.",
                $details,
                $absence
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence refusée avec succès.');
    }

    /**
     * Restore the specified absence.
     */
    public function restore(Absence $absence): RedirectResponse
    {
        $absence->is_deleted = 0;
        $absence->save();

        $user = Auth::user();
        $concernedUser = User::find($absence->user_id_salarie);
        $details = $this->initMail($absence);

        $recipients = array_filter([$user?->email, $concernedUser instanceof User ? $concernedUser->email : null]);
        if (! empty($recipients)) {
            Mail::to($recipients)->send(new InfoGeneriqueMail(
                'Absence restorée',
                "L'absence a été restorée.",
                $details,
                $absence
            ));
        }

        return redirect()->route('absence.index')->with('success', 'Absence restaurée.');
    }

    /**
     * Show the validation page for the specified absence.
     */
    public function showValidationPage(Absence $absence): Factory|View
    {
        return view('absence.confirm', compact('absence'));
    }

    public function downloadJustificatif(Absence $absence)
    {
        // Vérifier si l'utilisateur a le droit d'accéder au fichier
        if (Auth::id() !== $absence->user_id_salarie && !Auth::user()->isAn('admin')) {
            abort(403);
        }

        // Vérifier si le justificatif existe
        if (!$absence->justificatif_path || !Storage::disk('private')->exists($absence->justificatif_path)) {
            return redirect()->back()->with('error', 'Le justificatif n\'est pas disponible.');
        }

        return Storage::disk('private')->download($absence->justificatif_path);
    }

}
