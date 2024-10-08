<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AbsenceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
                'after_or_equal:'.Carbon::now()->toDateString(),
                'before_or_equal:'.Carbon::now()->addDays(60)->toDateString(),
            ],
            'date_absence_fin' => [
                'required',
                'date',
                'after:date_absence_debut',
                'before_or_equal:'.Carbon::parse($dateAbsenceDebut)->addDays(15)->toDateString(),
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'motif_id.required' => 'Veuillez sélectionner un motif d\'absence.',
            'user_id_salarie.required' => 'Veuillez sélectionner la personne absente.',
            'date_absence_debut.required' => 'Veuillez indiquer une date de début d\'absence.',
            'date_absence_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans les 60 prochains jours.',
            'date_absence_debut.before_or_equal' => 'La date de début doit être aujourd\'hui ou dans les 60 prochains jours.',
            'date_absence_fin.required' => 'Veuillez indiquer une date de fin d\'absence.',
            'date_absence_fin.after' => 'La date de fin doit être après la date de début.',
            'date_absence_fin.before_or_equal' => 'La date de fin ne peut pas être plus de 15 jours après la date de début.',
        ];
    }
}
