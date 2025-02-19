@extends('layouts.app')

@section('title', 'Accès Restreint')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-red-600 mb-4">Accès Restreint</h2>

            @if(session('error'))
                <p class="text-gray-600 mb-4">{{ session('error') }}</p>
            @endif

            <p class="text-gray-500 mb-6">Veuillez réessayer pendant les heures d'ouverture.</p>

            <form method="POST" action="{{ route('logout') }}" class="mb-4">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
