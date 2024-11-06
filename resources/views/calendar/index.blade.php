@extends('layouts.app')

@section('title', __('Calendar'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        @if(Auth::user()->isAn('admin'))
        <div class="mb-4">
            <select id="userFilter" class="border-gray-300 border-2 rounded-md p-2">
                <option value="all">Tous les utilisateurs</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div id="calendar"></div>
    </div>
</div>

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var selectedUser = 'all';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        events: function(info, successCallback, failureCallback) {
            fetch(`/api/absences?user=${selectedUser}`)
                .then(response => response.json())
                .then(data => {
                    const events = data.map(absence => ({
                        title: absence.motif.libelle,
                        start: absence.date_absence_debut,
                        end: absence.date_absence_fin,
                        color: absence.isValidated ? '#10B981' : '#EF4444',
                        extendedProps: {
                            status: absence.isValidated ? 'Validée' : 'En attente'
                        }
                    }));
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    failureCallback(error);
                });
        },
        eventDidMount: function(info) {
            const tooltip = new Tooltip(info.el, {
                title: `${info.event.title} - ${info.event.extendedProps.status}`,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        }
    });

    calendar.render();

    // Filtre pour les administrateurs
    if (document.getElementById('userFilter')) {
        document.getElementById('userFilter').addEventListener('change', function(e) {
            selectedUser = e.target.value;
            calendar.refetchEvents();
        });
    }
});
</script>
@endpush
@endsection
