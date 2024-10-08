@component('mail::message')
# Détails de l'absence

**Employé:** {{ $absence->user->nom }} {{ $absence->user->prenom }}
**Motif:** {{ $absence->motif->libelle ?? 'Aucun motif' }}
**Date de début:** {{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d-m-Y') }}
**Date de fin:** {{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d-m-Y') }}

@component('mail::button', ['url' => route('absence.confirmValidation', $absence->id)])
Valider l'absence
@endcomponent

@component('mail::button', ['url' => route('absence.edit', $absence->id)])
Modifier l'absence
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent
