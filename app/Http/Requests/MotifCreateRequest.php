<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotifCreateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Summary of rules
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'libelle' => 'required|string|max:30',
            'is_accessible_salarie' => 'boolean',
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
            'libelle.required' => 'Le nom du motif est obligatoire.',
            'libelle.max' => 'Le nom du motif ne doit pas dépasser 30 caractères.',
            'libelle.string' => 'Le nom du motif doit contenir uniquement des lettres',
        ];
    }
}
