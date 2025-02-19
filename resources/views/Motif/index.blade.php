@extends('layouts.app')

@section('title', __('Reasons List'))

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
@auth
<h1 class="font-bold text-center text-5xl m-4 text-white">{{__('Reasons List')}}</h1>
<div class="flex justify-end">
    <a href="{{route('motif.info')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2 mb-5">{{__('know more about reasons')}}</a>

    <a href="{{route('motif.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2 mr-[12.5%] mb-5">{{__('Add a reason')}}</a>
</div>
@endif
<div class="flex overflow-x-auto justify-center">
    <table class="w-9/12 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow-xl">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">{{__('Reason Name')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Accessibility')}}</th>
                <th scope="col" class="px-6 py-3">{{__('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($motifs as $motif)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-6 py-4 text-black">{{ $motif->libelle ?? 'Aucun nom' }}</td>
                    <td class="px-6 py-4 text-black">{{ $motif->is_accessible_salarie ? __('Accessible to employee') : __('Not accessible to employee') }}</td>
                    <td class="px-6 py-4">
                        @if (Auth::check() && Auth::user()->isA('admin'))
                            <a href="{{route('motif.edit', ['motif' => $motif])}}" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">{{__('Edit')}}</a>
                            <form action="{{ route('motif.destroy', $motif) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="{{__('Delete')}}" class="ml-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out">
                            </form>
                    </td>
                        @else
                        <td class="px-6 py-4">{{__('No action available')}}</td>
                        @endif
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center">{{__('No reason stored')}}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="w-full flex justify-center mt-5">
    <a href="{{route('accueil')}}" class="bg-gray-900 text-white p-2 rounded-md hover:shadow-2xl shadow-black">{{__('Go back')}}</a>
</div>
@endsection
