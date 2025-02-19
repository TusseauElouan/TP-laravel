@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
        Historique des Modifications du Planning
    </h1>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <table class="min-w-full border-collapse block md:table">
            <thead class="block md:table-header-group">
                <tr class="border border-gray-200 dark:border-gray-700 block md:table-row">
                    <th class="bg-gray-100 dark:bg-gray-700 text-left p-4 font-semibold text-gray-700 dark:text-gray-300 block md:table-cell">
                        Action
                    </th>
                    <th class="bg-gray-100 dark:bg-gray-700 text-left p-4 font-semibold text-gray-700 dark:text-gray-300 block md:table-cell">
                        Utilisateur
                    </th>
                    <th class="bg-gray-100 dark:bg-gray-700 text-left p-4 font-semibold text-gray-700 dark:text-gray-300 block md:table-cell">
                        Détails
                    </th>
                    <th class="bg-gray-100 dark:bg-gray-700 text-left p-4 font-semibold text-gray-700 dark:text-gray-300 block md:table-cell">
                        Date
                    </th>
                </tr>
            </thead>
            <tbody class="block md:table-row-group">
                @forelse($histories as $history)
                <tr class="border border-gray-200 dark:border-gray-700 block md:table-row">
                    <td class="p-4 text-gray-600 dark:text-gray-300 block md:table-cell">
                        {{ $history->action_type }}
                    </td>
                    <td class="p-4 text-gray-600 dark:text-gray-300 block md:table-cell">
                        {{ $history->user->prenom ?? 'Inconnu' }} {{ $history->user->nom ?? '' }}
                    </td>
                    <td class="p-4 text-gray-600 dark:text-gray-300 block md:table-cell">
                        <pre class="bg-gray-100 dark:bg-gray-700 rounded p-2 text-sm overflow-auto">
                            {{ json_encode($history->details, JSON_PRETTY_PRINT) }}
                        </pre>
                    </td>
                    <td class="p-4 text-gray-600 dark:text-gray-300 block md:table-cell">
                        {{ $history->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr class="border border-gray-200 dark:border-gray-700 block md:table-row">
                    <td colspan="4" class="p-4 text-gray-600 dark:text-gray-300 text-center block md:table-cell">
                        Aucun historique disponible.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $histories->links() }}
    </div>
</div>
@endsection
