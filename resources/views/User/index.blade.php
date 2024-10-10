@extends('layouts.app')
@section('title', __('Users List'))

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
@if(Auth::check() && Auth::user()->isA('admin'))
<h1 class="font-bold text-center text-5xl m-4">{{__('Users List')}}</h1>
<div class="flex overflow-x-auto justify-center">
    <table class="w-9/12 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-xl">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">{{__('First Name')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Last Name')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4">{{ $user->prenom }}</td>
                    <td class="px-6 py-4">{{ $user->nom }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('user.show', $user) }}">{{__('User details')}}</a>
                        <a href="{{ route('user.edit', $user)}}" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">{{__('Edit')}}</a>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="3">{{__('No user stored')}}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@else
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8 mt-8">
    <h1 class="font-bold text-center text-5xl mb-6 text-gray-800">{{__('User details')}}</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Prénom -->
        <div>
            <span class="font-semibold text-lg text-gray-700">{{__('First Name')}}</span>
            <p class="text-gray-600 text-xl mt-1">{{ Auth::user()->prenom }}</p>
        </div>

        <!-- Email -->
        <div>
            <span class="font-semibold text-lg text-gray-700">{{__('Last Name')}}</span>
            <p class="text-gray-600 text-xl mt-1">{{ Auth::user()->email }}</p>
        </div>
    </div>

    <!-- Absences -->
    <div class="mt-8">
        <span class="font-semibold text-lg text-gray-700">{{(__('Absences'))}}</span>
        <ul class="list-disc pl-5 text-gray-600 text-lg mt-2">
            @if ($absences->isEmpty())
                <li>{{__('No absence assigned')}}</li>
            @else
                @foreach ($absences as $absence)
                    @if ($absence->user_id_salarie == Auth::user()->id)
                        @if($absence->isValidated)
                            <li class="text-green-600">{{__('Reason')}} : {{ $absence->motif->libelle}} / {{__('Start date')}} : {{$absence->date_absence_debut}} / {{__('End date')}} : {{$absence->date_absence_fin}}</li>
                        @else
                            <li class="text-red-600">{{__('Reason')}} : {{ $absence->motif->libelle}} / {{__('Start date')}} : {{$absence->date_absence_debut}} / {{__('End date')}} : {{$absence->date_absence_fin}}</li>
                        @endif
                    @endif
                @endforeach
            @endif
        </ul>
    </div>

    <!-- Bouton Edit -->
    <div class="mt-8 text-center">
        <a href="{{ route('user.edit', Auth::user()->id) }}" class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out shadow-md">
            {{__('Edit')}}
        </a>
    </div>
</div>
@endif
<div class="w-full flex justify-center mt-5">
    <a href="{{route('accueil')}}" class="bg-gray-900 text-white p-2 rounded-md hover:shadow-2xl shadow-black ">{{__('Go back')}}</a>
</div>
@endsection
