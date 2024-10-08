@component('mail::message')
# Détails de l'absence modifiée.


**Employé:** {{ $absence->user->nom }} {{ $absence->user->prenom }}
**Motif:** {{ $absence->motif->libelle ?? 'Aucun motif' }}
**Date de début:** {{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d-m-Y') }}
**Date de fin:** {{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d-m-Y') }}

Merci,
{{ config('app.name') }}
@endcomponent
