@extends('layouts.app')

@section('title', __('Gestion des plages horaires'))

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Notifications --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Configuration des plages horaires d'accès</h1>

        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure de début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure de fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4">{{ $user->prenom }} {{ $user->nom }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($user->timeAccess->start_time ?? '08:00')->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($user->timeAccess->end_time ?? '18:00')->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->timeAccess && $user->timeAccess->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Actif</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openModal('modal-{{ $user->id }}')"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifier
                            </button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div id="modal-{{ $user->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    Modifier les horaires pour {{ $user->prenom }} {{ $user->nom }}
                                </h3>
                                <form action="{{ route('time-access.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Heure de début
                                        </label>
                                        <input type="time"
                                               name="start_time"
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               value="{{ old('start_time', optional($user->timeAccess)->start_time ?? '08:00') }}"
                                               required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                            Heure de fin
                                        </label>
                                        <input type="time"
                                               name="end_time"
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               value="{{ old('end_time', optional($user->timeAccess)->end_time ?? '18:00') }}"
                                               required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox"
                                                   name="is_active"
                                                   value="1"
                                                   class="form-checkbox h-4 w-4"
                                                   {{ ($user->timeAccess && $user->timeAccess->is_active) ? 'checked' : '' }}>
                                            <span class="ml-2">Actif</span>
                                        </label>
                                    </div>

                                    <div class="flex justify-end space-x-3">
                                        <button type="button"
                                                onclick="closeModal('modal-{{ $user->id }}')"
                                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                            Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    }

    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.fixed').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endpush
@endsection
