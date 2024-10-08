@extends('layouts.app')

@section('title', 'Utilisateur')

@section('content')
<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Détails de l'utilisateur</h1>

    <ul class="space-y-4">
        <li class="text-gray-700"><span class="font-semibold">Prénom:</span> {{ $user->prenom }}</li>
        <li class="text-gray-700"><span class="font-semibold">Email:</span> {{ $user->email }}</li>
        <li class="text-gray-700"><span class="font-semibold">Absences:</span>
            <ul class="ml-4 mt-2 space-y-2">
                @if ($absences->isEmpty())
                    <li class="text-red-500">Aucune absence enregistrée.</li>
                @else
                    @foreach ($absences as $absence)
                        <li class="bg-gray-100 p-2 rounded-md shadow-sm"><p>Motif :{{ $absence->motif->libelle ?? 'Aucun motif assigné.' }}</p><p>Début de l'absence : {{$absence->date_absence_debut}}</p><p>Fin de l'absence : {{ $absence->date_absence_fin}}</p></li>
                    @endforeach
                @endif
            </ul>
        </li>
    </ul>

    <div class="flex justify-between mt-8">
        {{-- <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow">Modifier</a>
        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow">Supprimer</button>
        </form> --}}
        <a href="/user" class="bg-gray-800 hover:bg-gray-900 text-white py-2 px-4 rounded-lg shadow">Retour</a>
    </div>
</div>
@endsection

