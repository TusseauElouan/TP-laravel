@extends('layouts.app')

@section('title', __('Absences List'))

@section('content')
@if(session('success') || session('error'))
<div id="alert-message" class="flex justify-between items-center w-full max-w-lg mx-auto mt-6 p-4 mb-6 text-sm text-white rounded-lg shadow-lg @if(session('success')) bg-green-500 @else bg-red-500 @endif">
    <span>
        {{ session('success') ?? session('error') }}
    </span>
    <button type="button" class="ml-4 font-bold text-xl" onclick="document.getElementById('alert-message').remove();">
        &times;
    </button>
</div>
@endif

<h1 class="font-bold text-center text-5xl m-4">{{__('Absences List')}}</h1>

<!-- Afficher le bouton "Ajouter une absence" seulement si l'utilisateur est connecté -->
@auth
<div class="flex justify-end">
    <a href="{{route('absence.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2 mr-[12.5%] mb-5">{{__('Add an absence')}}</a>
</div>


<div class="flex overflow-x-auto justify-center">
    <table class="w-9/12 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-xl">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">{{__('Reason')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Start date')}}</th>
                <th scope="col" class="px-6 py-3">{{__('End date')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Employee')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Actions')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Status')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $absence)
                @if ($absence->user_id_salarie == Auth::user()->id || Auth::user()->isAn('admin'))
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $absence->motif->libelle ?? 'Aucun motif assigné' }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4">{{ $absence->user->nom ?? __('Last name of the user not found') }} {{ $absence->user->prenom ?? __('First name of the user not found')}}</td>
                        <td class="px-6 py-4">
                            @auth
                            @if(!$absence->is_deleted)
                                @if (!$absence->isValidated)
                                    <a href="{{route('absence.edit', ['absence' => $absence])}}" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">{{__('Edit')}}</a>
                                @endif
                                @if(Auth::user()->isA('admin'))
                                    @if (!$absence->is_deleted)
                                    <form action="{{ route('absence.destroy', $absence) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" value="{{__('Delete')}}" class="ml-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">
                                    </form>
                                    @endif
                                    @if (!$absence->isValidated)
                                    <form action="{{ route('absence.validate', ['absence' => $absence]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="ml-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">
                                            {{__('Validate') }}
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            @else
                                @if(Auth::user()->isA('admin'))
                                    <form action="{{ route('absence.restore', $absence) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="submit" value="{{__('Restore')}}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">
                                    </form>
                                @endif
                            @endif
                            @endauth
                        </td>
                        <td>
                            @if($absence->isValidated)
                                <p class="text-green-600">{{__('Validated')}}</p>
                            @else
                                <p class="text-blue-600">{{__('On hold')}}</p>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">Aucune absence enregistrée.</td>
                </tr>

            @endforelse
        </tbody>
    </table>
</div>
@endauth
@if(!Auth::check())
<div class="w-full flex justify-center mt-5">
    <p>Vous n'êtes pas connecté. <a href="{{route('login')}}">Connectez-vous.</a></p>
</div>
@endif
<div class="w-full flex justify-center mt-5">
    <a href="{{route('accueil')}}" class="bg-gray-900 text-white p-2 rounded-md hover:shadow-2xl shadow-black">Retour</a>
</div>

@endsection
