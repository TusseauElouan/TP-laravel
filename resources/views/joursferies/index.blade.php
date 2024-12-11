{{-- resources/views/jours-feries/index.blade.php --}}
@extends('layouts.app')

@section('title', __('Configuration des jours fériés'))

@section('content')
<div class="py-12">  {{-- Ajout d'un padding en haut pour laisser de l'espace sous la navbar --}}
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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">  {{-- Conteneur principal avec marges responsives --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">  {{-- Carte principale --}}
            <h1 class="font-bold text-center text-5xl mb-8">{{__('Configuration des jours fériés')}}</h1>

            <div class="flex justify-end mb-6">
                <button id="addHolidayBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{__('Ajouter un jour férié')}}
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">{{__('Nom')}}</th>
                            <th scope="col" class="px-6 py-3">{{__('Date')}}</th>
                            <th scope="col" class="px-6 py-3">{{__('Récurrent')}}</th>
                            <th scope="col" class="px-6 py-3">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($joursFeries as $jourFerie)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $jourFerie->nom }}</td>
                                <td class="px-6 py-4">{{ $jourFerie->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $jourFerie->is_recurring ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $jourFerie->is_recurring ? __('Oui') : __('Non') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="edit-holiday ml-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out" data-id="{{ $jourFerie->id }}">
                                        {{__('Modifier')}}
                                    </button>
                                    <button class="delete-holiday ml-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded-lg transition duration-200 ease-in-out" data-id="{{ $jourFerie->id }}">
                                        {{__('Supprimer')}}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center">{{__('Aucun jour férié enregistré')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-center">
                <a href="{{route('accueil')}}" class="inline-block bg-gray-900 text-white p-2 rounded-md hover:shadow-2xl shadow-black">
                    {{__('Retour')}}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="holidayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">{{__('Ajouter un jour férié')}}</h3>
            <form id="holidayForm" class="mt-4">
                @csrf
                <input type="hidden" id="holidayId">
                <div class="flex flex-col">
                    <label for="nom" class="text-xl mx-1 mb-2">{{__('Nom')}}</label>
                    <input type="text" id="nom" name="nom" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" required>
                </div>
                <div class="flex flex-col mt-4">
                    <label for="date" class="text-xl mx-1 mb-2">{{__('Date')}}</label>
                    <input type="date" id="date" name="date" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" required>
                </div>
                <div class="flex items-center mt-4">
                    <input type="checkbox" id="is_recurring" name="is_recurring" class="w-6 h-6 text-blue-600 bg-gray-200 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_recurring" class="text-xl mx-1 mb-2">{{__('Récurrent')}}</label>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="button" id="closeModal" class="mr-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        {{__('Annuler')}}
                    </button>
                    <button type="submit" class="bg-gray-900 rounded-md text-white py-2 px-4 cursor-pointer">
                        {{__('Sauvegarder')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('holidayModal');
    const form = document.getElementById('holidayForm');
    const addBtn = document.getElementById('addHolidayBtn');
    const closeBtn = document.getElementById('closeModal');

    // Ouvrir le modal pour ajouter
    addBtn.addEventListener('click', () => {
        document.getElementById('modalTitle').textContent = '{{__("Ajouter un jour férié")}}';
        document.getElementById('holidayId').value = '';
        form.reset();
        modal.classList.remove('hidden');
    });

    // Fermer le modal
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Gestionnaire de soumission du formulaire
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const holidayId = document.getElementById('holidayId').value;
        const formData = {
            nom: document.getElementById('nom').value,
            date: document.getElementById('date').value,
            is_recurring: document.getElementById('is_recurring').checked
        };

        try {
            const url = holidayId ? `/api/joursferies/${holidayId}` : '/api/joursferies';
            const method = holidayId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('{{__("Erreur lors de la sauvegarde")}}');

            modal.classList.add('hidden');
            window.location.reload();
        } catch (error) {
            console.error('Erreur:', error);
            alert('{{__("Une erreur est survenue")}}');
        }
    });

    // Gestionnaires pour les boutons d'édition
    document.querySelectorAll('.edit-holiday').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const id = e.target.dataset.id;
            try {
                const response = await fetch(`/api/joursferies/${id}`);
                const data = await response.json();

                document.getElementById('holidayId').value = data.id;
                document.getElementById('nom').value = data.nom;
                document.getElementById('date').value = data.date;
                document.getElementById('is_recurring').checked = data.is_recurring;
                document.getElementById('modalTitle').textContent = '{{__("Modifier un jour férié")}}';

                modal.classList.remove('hidden');
            } catch (error) {
                console.error('Erreur:', error);
                alert('{{__("Une erreur est survenue")}}');
            }
        });
    });

    // Gestionnaires pour les boutons de suppression
    document.querySelectorAll('.delete-holiday').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            if (confirm('{{__("Êtes-vous sûr de vouloir supprimer ce jour férié ?")}}')) {
                const id = e.target.dataset.id;
                try {
                    const response = await fetch(`/api/joursferies/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error('{{__("Erreur lors de la suppression")}}');

                    window.location.reload();
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('{{__("Une erreur est survenue lors de la suppression")}}');
                }
            }
        });
    });

    // Fermer le modal en cliquant en dehors
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Fermer le modal avec la touche Echap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection
