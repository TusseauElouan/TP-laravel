@extends('layouts.app')

@section('title', __('Absences List'))

@section('content')

@if(session('success') || session('error'))
<div id="alert-message" class="max-w-lg mx-auto mt-6 p-4 mb-6 text-sm text-white rounded-lg shadow-lg
    {{ session('success') ? 'bg-green-500' : 'bg-red-500' }}">
    <div class="flex justify-between items-center">
        <span>{{ session('success') ?? session('error') }}</span>
        <button type="button" class="ml-4 font-bold text-xl" onclick="document.getElementById('alert-message').remove();">
            &times;
        </button>
    </div>
</div>
@endif

<!-- Titre -->
<h1 class="text-center text-4xl font-bold text-gray-900 dark:text-gray-100 my-6">{{__('Absences List')}}</h1>

<!-- Boutons Actions -->
<div class="flex justify-end space-x-4 mb-5 pr-16">
    <a href="{{ route('absences.export') }}" class="flex items-center bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        {{__('Export absences')}}
    </a>
    <a href="{{ route('absence.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
        {{__('Add an absence')}}
    </a>
</div>

<!-- Table Responsive -->
<div class="overflow-x-auto">
    <table class="w-11/12 mx-auto text-sm text-left text-gray-600 dark:text-gray-300 border-collapse shadow-lg rounded-lg">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-800 dark:text-gray-300">
            <tr class="border-b dark:border-gray-700">
                <th class="px-6 py-3">{{__('Reason')}}</th>
                <th class="px-6 py-3">{{__('Start date')}}</th>
                <th class="px-6 py-3">{{__('End date')}}</th>
                <th class="px-6 py-3">{{__('Employee')}}</th>
                <th class="px-6 py-3">{{__('Justificatif')}}</th>
                <th class="px-6 py-3">{{__('Actions')}}</th>
                <th class="px-6 py-3">{{__('Status')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($absences as $absence)
                @if ($absence->user_id_salarie == Auth::user()->id || Auth::user()->isAn('admin'))
                    <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-black">{{ $absence->motif->libelle ?? __('No reason assigned') }}</td>
                        <td class="px-6 py-4 text-black">{{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-black">{{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-black">{{ $absence->user->nom ?? __('Last name not found') }} {{ $absence->user->prenom ?? __('First name not found')}}</td>
                        <td class="px-6 py-4 text-black">
                            @if($absence->justificatif_path)
                                <a href="{{ route('absence.justificatif.download', $absence) }}" class="text-blue-500 hover:text-blue-700 underline flex items-center">
                                    {{ __('Download justificatif') }}
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            @else
                                {{ __('No document') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            @if(!$absence->is_deleted)
                                @if (!$absence->isValidated && !$absence->isRefused)
                                    <a href="{{ route('absence.edit', $absence) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition">
                                        {{__('Edit')}}
                                    </a>
                                @endif
                                @if(Auth::user()->isAn('admin'))
                                    <form action="{{ route('absence.destroy', $absence) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-1 px-3 rounded-lg transition">
                                            {{__('Delete')}}
                                        </button>
                                    </form>
                                    @if (!$absence->isValidated && !$absence->isRefused)
                                        <form action="{{ route('absence.validate', $absence) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded-lg transition">
                                                {{__('Validate')}}
                                            </button>
                                        </form>
                                        <form action="{{ route('absence.refuse', $absence) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-lg transition">
                                                {{__('Refuse')}}
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @else
                                <form action="{{ route('absence.restore', $absence) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-1 px-3 rounded-lg transition">
                                        {{__('Restore')}}
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($absence->isValidated)
                                <p class="text-green-600">{{__('Validated')}}</p>
                            @elseif($absence->isRefused)
                                <p class="text-red-600">{{__('Refused')}}</p>
                            @elseif($absence->is_deleted)
                                <p class="text-red-600">{{__('Supprimee')}}</p>
                            @else
                                <p class="text-blue-600">{{__('On hold')}}</p>
                            @endif
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center">{{__('No absence stored.')}}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="w-full flex justify-center mt-5">
    <a href="{{ route('accueil') }}" class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:shadow-xl">
        {{__('Go back')}}
    </a>
</div>

@endsection
