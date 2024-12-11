@extends('layouts.app')

@section('title', __('Préférences de couleurs'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('Personnalisation des couleurs du calendrier') }}</h1>

        <form action="{{ route('preferences.colors.store') }}" method="POST">
            @csrf

            @foreach($motifs as $motif)
            <div class="mb-6 p-4 border rounded-lg">
                <h2 class="text-lg font-semibold mb-4">{{ $motif->libelle }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Couleur de fond') }}
                        </label>
                        <input
                            type="color"
                            name="colors[{{ $motif->id }}][background_color]"
                            value="{{ $preferences[$motif->id]->background_color ?? '#FFFFFF' }}"
                            class="w-full h-10 rounded cursor-pointer"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Couleur de texte') }}
                        </label>
                        <input
                            type="color"
                            name="colors[{{ $motif->id }}][text_color]"
                            value="{{ $preferences[$motif->id]->text_color ?? '#000000' }}"
                            class="w-full h-10 rounded cursor-pointer"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('Couleur de bordure') }}
                        </label>
                        <input
                            type="color"
                            name="colors[{{ $motif->id }}][border_color]"
                            value="{{ $preferences[$motif->id]->border_color ?? '#CCCCCC' }}"
                            class="w-full h-10 rounded cursor-pointer"
                        >
                    </div>
                </div>

                <!-- Aperçu en direct -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Aperçu') }}
                    </label>
                    <div class="preview-event p-2 rounded border"
                         id="preview-{{ $motif->id }}"
                         style="background-color: {{ $preferences[$motif->id]->background_color ?? '#FFFFFF' }};
                                color: {{ $preferences[$motif->id]->text_color ?? '#000000' }};
                                border-color: {{ $preferences[$motif->id]->border_color ?? '#CCCCCC' }}">
                        {{ $motif->libelle }} - Exemple d'événement
                    </div>
                </div>
            </div>
            @endforeach

            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('Enregistrer les préférences') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[type="color"]');

    colorInputs.forEach(input => {
        input.addEventListener('input', function() {
            const motifId = this.name.match(/colors\[(\d+)\]/)[1];
            const previewElement = document.getElementById(`preview-${motifId}`);
            const colorType = this.name.match(/\[(background|text|border)_color\]/)[1];

            if (colorType === 'background') {
                previewElement.style.backgroundColor = this.value;
            } else if (colorType === 'text') {
                previewElement.style.color = this.value;
            } else if (colorType === 'border') {
                previewElement.style.borderColor = this.value;
            }
        });
    });
});
</script>
@endpush
@endsection
