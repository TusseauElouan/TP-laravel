@extends('layouts.app')

@section('title', 'Confirmer l\'absence')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold mb-4 text-gray-800">Confirmer la validation de l'absence</h1>
        <p class="mb-6 text-gray-600">
            Voulez-vous vraiment valider l'absence de <span class="font-bold">{{ $absence->user->nom }} {{ $absence->user->prenom}}</span>
            du <span class="font-bold">{{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d/m/Y') }}</span>
            au <span class="font-bold">{{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d/m/Y') }}</span> ?
        </p>

        <form action="{{ route('absence.validate', $absence->id) }}" method="POST">
            @csrf
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    Valider l'absence
                </button>
                <a href="{{ route('dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-md transition duration-200">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
