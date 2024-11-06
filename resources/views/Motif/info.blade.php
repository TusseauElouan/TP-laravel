@extends('layouts.app')

@section('title', __('absence.reasons_title'))

@section('content')
<div class="container mx-auto mt-10 p-8 bg-white rounded-lg shadow-lg">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-12">{{ __('know more about reasons') }}</h1>

    <!-- Congé annuel -->
    @if($motifs->where('libelle', 'Congé annuel')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.annual_leave') }}</h2>
        <p class="text-gray-700">{{ __('absence.annual_leave_desc') }}</p>
    </section>
    @endif

    <!-- Maladie -->
    @if($motifs->where('libelle', 'Maladie')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.sick_leave') }}</h2>
        <p class="text-gray-700">{{ __('absence.sick_leave_desc') }}</p>
    </section>
    @endif

    <!-- Congé sans solde -->
    @if($motifs->where('libelle', 'Congé sans solde')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.unpaid_leave') }}</h2>
        <p class="text-gray-700">{{ __('absence.unpaid_leave_desc') }}</p>
    </section>
    @endif

    <!-- Formation -->
    @if($motifs->where('libelle', 'Formation')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.training') }}</h2>
        <p class="text-gray-700">{{ __('absence.training_desc') }}</p>
    </section>
    @endif

    <!-- Congé maternité -->
    @if($motifs->where('libelle', 'Congé maternité')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.maternity_leave') }}</h2>
        <p class="text-gray-700">{{ __('absence.maternity_leave_desc') }}</p>
    </section>
    @endif

    <!-- Absence exceptionnelle -->
    @if($motifs->where('libelle', 'Absence exceptionnelle')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.exceptional_leave') }}</h2>
        <p class="text-gray-700">{{ __('absence.exceptional_leave_desc') }}</p>
    </section>
    @endif

    <!-- Mission extérieure -->
    @if($motifs->where('libelle', 'Mission extérieure')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.external_mission') }}</h2>
        <p class="text-gray-700">{{ __('absence.external_mission_desc') }}</p>
    </section>
    @endif

    <!-- Télétravail -->
    @if($motifs->where('libelle', 'Télétravail')->first()->is_accessible_salarie)
    <section class="mb-8">
        <h2 class="text-2xl font-semibold text-blue-600 mb-2">{{ __('absence.remote_work') }}</h2>
        <p class="text-gray-700">{{ __('absence.remote_work_desc') }}</p>
    </section>
    @endif
</div>
@endsection
