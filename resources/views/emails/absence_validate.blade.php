<x-mail::message>
# Absence validée

Bonjour {{ $absence->user->nom }} {{ $absence->user->prenom}},

Nous vous informons que votre absence du {{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d/m/Y') }} a été modifiée.

<x-mail::button :url="route('absence.index')">
Voir la liste des absences
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
