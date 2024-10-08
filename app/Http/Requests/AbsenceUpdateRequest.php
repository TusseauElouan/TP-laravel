<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AbsenceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Summary of rules
     *
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        $dateAbsenceDebut = $this->input('date_absence_debut');

        return [
            'motif_id' => 'required|exists:motifs,id',
            'user_id_salarie' => 'required|exists:users,id',
            'date_absence_debut' => [
                'required',
                'date',
                'after_or_equal:' . Carbon::now()->toDateString(),
                'before_or_equal:' . Carbon::now()->addDays(60)->toDateString(),
            ],
            'date_absence_fin' => [
                'required',
                'date',
                'after:date_absence_debut',
                'before_or_equal:' . Carbon::parse($dateAbsenceDebut)->addDays(15)->toDateString(),
            ],
        ];
    }

    /**
     * Summary of messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'motif_id.required' => 'Le motif est requis.',
            'user_id_salarie.required' => 'L\'utilisateur est requis.',
            'date_absence_debut.required' => 'La date de début est requise.',
            'date_absence_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans les 60 prochains jours.',
            'date_absence_debut.before_or_equal' => 'La date de début doit être aujourd\'hui ou dans les 60 prochains jours.',
            'date_absence_fin.required' => 'La date de fin est requise.',
            'date_absence_fin.after' => 'La date de fin doit être après la date de début.',
            'date_absence_fin.before_or_equal' => 'La date de fin doit être dans les 15 jours suivant la date de début.',
        ];
    }
}
