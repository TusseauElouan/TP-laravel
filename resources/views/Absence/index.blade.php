@extends('layouts.app')

@section('title', __('Absences List'))

@section('content')
<p class="hidden">{{$count = 0}}</p>
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
<div class="flex justify-end gap-2 mr-[12.5%] mb-5">
    <a href="{{route('absences.export')}}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200 ease-in-out flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
        {{__('Export absences')}}
    </a>
    <a href="{{route('absence.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{__('Add an absence')}}
    </a>
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
            @foreach($absences as $absence)
                @if ($absence->user_id_salarie == Auth::user()->id || Auth::user()->isAn('admin'))
                    <p class="hidden">{{$count++}}</p>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $absence->motif->libelle ?? __('No reason assigned') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absence->date_absence_debut)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($absence->date_absence_fin)->format('d-m-Y') }}</td>
                        <td class="px-6 py-4">{{ $absence->user->nom ?? __('Last name of the user not found') }} {{ $absence->user->prenom ?? __('First name of the user not found')}}</td>
                        <td class="px-6 py-4">
                            @if(!$absence->is_deleted)
                                @if (!$absence->isValidated)
                                    <a href="{{route('absence.edit', ['absence' => $absence])}}" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">{{__('Edit')}}</a>
                                @endif
                                @if(Auth::user()->isAn('admin'))
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
                                @if(Auth::user()->isAn('admin'))
                                    <form action="{{ route('absence.restore', $absence) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="submit" value="{{__('Restore')}}" class="ml-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">
                                    </form>
                                @endif
                            @endif
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
            @endforeach
            @if($count == 0)
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">{{__('No absence stored.')}}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="w-full flex justify-center mt-5">
    <a href="{{route('accueil')}}" class="bg-gray-900 text-white p-2 rounded-md hover:shadow-2xl shadow-black">{{__('Go back')}}</a>
</div>
@endsection
