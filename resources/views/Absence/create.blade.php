@extends('layouts.app')
@section('title', __('Add an absence'))

@section('content')
    <div class="grid place-content-center text-center h-screen">
        <form method="POST" action="{{ route('absence.store') }}" enctype="multipart/form-data"
            class="flex flex-col border-gray-300 border-2 rounded-md space-y-6 p-10 w-80">
            @csrf

            <!-- Sélection de l'utilisateur -->
            <div class="flex flex-col">
                <label for="user_id_salarie" class="text-xl mx-1 mb-2">{{ __('User') }}</label>
                <select id="user_id_salarie" name="user_id_salarie"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @if (Auth::check() && Auth::user()->isA('admin'))
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nom }} {{ $user->prenom }}</option>
                        @endforeach
                    @else
                        <option value="{{ Auth::user()->id }}">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</option>
                    @endif
                </select>
                @error('user_id_salarie')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Sélection du motif -->
            <div class="flex flex-col">
                <label for="motif_id" class="text-xl mx-1 mb-2">{{ __('Reason') }}</label>
                <select id="motif_id" name="motif_id"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @foreach ($motifs as $motif)
                        <option value="{{ $motif->id }}">{{ $motif->libelle }}</option>
                    @endforeach
                </select>
                @error('motif_id')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Champ pour le motif personnalisé (caché par défaut) -->
            <div id="custom_motif_container" class="flex flex-col hidden">
                <label for="custom_motif" class="text-xl mx-1 mb-2">{{ __('Custom reason') }}</label>
                <input type="text" id="custom_motif" name="custom_motif"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                @error('custom_motif')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date de début -->
            <div class="flex flex-col">
                <label for="date_absence_debut" class="text-xl mx-1 mb-2">{{ __('Start date') }}</label>
                <input type="date" id="date_absence_debut" name="date_absence_debut"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                @error('date_absence_debut')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date de fin -->
            <div class="flex flex-col">
                <label for="date_absence_fin" class="text-xl mx-1 mb-2">{{ __('End date') }}</label>
                <input type="date" id="date_absence_fin" name="date_absence_fin"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                @error('date_absence_fin')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Upload du justificatif -->
            <div id="justificatif_container" class="flex flex-col">
                <label for="justificatif" class="text-xl mx-1 mb-2">{{ __('Justificatif') }}</label>
                <input type="file" id="justificatif" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                    class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                    max="5242880">
                <p class="text-sm text-gray-500 mt-1">{{ __('Accepted formats: PDF, JPEG, PNG (max 5MB)') }}</p>
                @error('justificatif')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>

            <!-- Bouton de soumission -->
            <input type="submit" value="{{ __('Add') }}"
                class="bg-gray-900 rounded-md text-white py-2 cursor-pointer">
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const motifSelect = document.getElementById('motif_id');
            const customMotifContainer = document.getElementById('custom_motif_container');
            const customMotifInput = document.getElementById('custom_motif');
            const justificatifContainer = document.getElementById('justificatif_container');

            motifSelect.addEventListener('change', function() {
                if (this.value === '9') {
                    customMotifContainer.classList.remove('hidden');
                    justificatifContainer.classList.add('hidden');
                    customMotifInput.setAttribute('required', 'required');
                } else {
                    customMotifContainer.classList.add('hidden');
                    justificatifContainer.classList.remove('hidden');
                    customMotifInput.removeAttribute('required');
                }
            });
        });
    </script>
@endsection
