@extends('layouts.app')

@section('title', __('Calendar'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            @if(Auth::user()->isAn('admin'))
            <div>
                <select id="userFilter" class="border-gray-300 border-2 rounded-md p-2">
                    <option value="all">{{ __('All users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <a href="{{ route('preferences.colors') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    {{ __('Color Preferences') }}
                </a>
            </div>
        </div>

        <div id="calendar" class="min-h-[600px]"></div>
    </div>
</div>

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
<link href='https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.10/main.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .fc-event:hover {
        filter: brightness(0.9);
    }
    .fc .fc-button {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    .fc .fc-button:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #1e40af;
        border-color: #1e40af;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.10/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var selectedUser = 'all';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'multiMonthYear,dayGridMonth,dayGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            year: 'Année',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        firstDay: 1,
        views: {
            timeGridDay: {
                titleFormat: { year: 'numeric', month: 'long', day: 'numeric' }
            },
            multiMonthYear: {
                titleFormat: { year: 'numeric' }
            }
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch(`/api/absences?user=${selectedUser}`)
                .then(response => response.json())
                .then(data => {
                    const events = data.map(absence => {
                        // Utiliser les préférences de couleur de l'utilisateur si disponibles
                        const colors = absence.color_preference ?? {
                            background_color: absence.isValidated ? '#10B981' : '#EF4444',
                            text_color: '#FFFFFF',
                            border_color: absence.isValidated ? '#059669' : '#DC2626'
                        };

                        return {
                            title: `${absence.motif.libelle} - ${absence.user.prenom} ${absence.user.nom}`,
                            start: absence.date_absence_debut,
                            end: absence.date_absence_fin,
                            backgroundColor: colors.background_color,
                            textColor: colors.text_color,
                            borderColor: colors.border_color,
                            allDay: true,
                            extendedProps: {
                                status: absence.isValidated ? 'Validée' : 'En attente',
                                user: `${absence.user.prenom} ${absence.user.nom}`,
                                motif: absence.motif.libelle,
                                justificatif: absence.justificatif_path
                            }
                        };
                    });
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error:', error);
                    failureCallback(error);
                });
        },
        eventDidMount: function(info) {
            const tooltipContent = [
                `Utilisateur: ${info.event.extendedProps.user}`,
                `Motif: ${info.event.extendedProps.motif}`,
                `Statut: ${info.event.extendedProps.status}`,
                info.event.extendedProps.justificatif ? 'Justificatif disponible' : 'Pas de justificatif'
            ].join('\n');

            info.el.title = tooltipContent;
        },
        eventClick: function(info) {
            if (info.event.extendedProps.justificatif) {
                window.location.href = `/absence/download/${info.event.id}`;
            }
        },
        dayMaxEvents: true,
        multiMonthMaxEvents: 4,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
    });

    calendar.render();

    var userFilter = document.getElementById('userFilter');
    if (userFilter) {
        userFilter.addEventListener('change', function(e) {
            selectedUser = e.target.value;
            calendar.refetchEvents();
        });
    }
});
</script>
@endpush
@endsection
