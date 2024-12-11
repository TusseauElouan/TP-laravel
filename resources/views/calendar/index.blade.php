@extends('layouts.app')

@section('title', __('Calendar'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            @if (Auth::user()->isAn('admin'))
                <div class="mb-4">
                    <select id="userFilter" class="border-gray-300 border-2 rounded-md p-2">
                        <option value="all">Tous les utilisateurs</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div id="calendar" class="min-h-[600px]"></div>
        </div>
    </div>

    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/main.min.css' rel='stylesheet' />
        <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.10/main.min.css' rel='stylesheet' />
        <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.10/main.min.css' rel='stylesheet' />
        <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/multimonth@6.1.10/main.min.css' rel='stylesheet' />
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
                            titleFormat: {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            }
                        },
                        multiMonthYear: {
                            titleFormat: {
                                year: 'numeric'
                            }
                        }
                    },
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch(`/api/absences/${selectedUser}`)
                            .then(response => response.json())
                            .then(data => {
                                // Combine les absences et les jours fériés
                                const allEvents = [...data.absences, ...data.holidays];
                                successCallback(allEvents);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                failureCallback(error);
                            });
                    },
                    eventDidMount: function(info) {
                        if (info.event.extendedProps.type === 'absence') {
                            info.el.title = `Utilisateur: ${info.event.extendedProps.user}\nMotif: ${info.event.extendedProps.motif}\nStatut: ${info.event.extendedProps.status}\nDébut: ${info.event.extendedProps.dateDebut}\nFin: ${info.event.extendedProps.dateFin}`;
                        } else if (info.event.extendedProps.type === 'holiday') {
                            info.el.title = `${info.event.title}${info.event.extendedProps.recurring ? ' (Récurrent)' : ''}\nDate: ${info.event.extendedProps.date}`;
                        }
                    },
                    dayMaxEvents: true,
                    multiMonthMaxEvents: 4,
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        meridiem: false,
                        hour12: false
                    }
                });

                calendar.render();

                // Filtre pour les administrateurs
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
