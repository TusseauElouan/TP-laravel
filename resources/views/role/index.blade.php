@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- Titre -->
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">{{ __('Roles List') }}</h1>

    <!-- Bouton Ajouter un rôle -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('role.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out shadow-md">
            + {{ __('Create Role') }}
        </a>
    </div>

    <!-- Table Responsive -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <table class="w-full text-left text-gray-600 dark:text-gray-300 border-collapse">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                <tr class="border-b dark:border-gray-600">
                    <th class="px-6 py-3">{{ __('Name') }}</th>
                    <th class="px-6 py-3">{{ __('Title') }}</th>
                    <th class="px-6 py-3 text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 text-black">{{ $role->name }}</td>
                    <td class="px-6 py-4 text-black">{{ $role->title }}</td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('role.edit', $role->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-300 ease-in-out shadow-sm">
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('role.destroy', $role->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-300 ease-in-out shadow-sm"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
