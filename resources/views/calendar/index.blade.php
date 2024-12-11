{{-- resources/views/calendar/index.blade.php --}}
@extends('layouts.app')

@section('title', __('Calendar'))

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                @if (Auth::user()->isAn('admin'))
                    <div>
                        <select id="userFilter" class="border-gray-300 border-2 rounded-md p-2">
                            <option value="all">{{ __('All users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div>
                    <a href="{{ route('preferences.colors') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
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
                        Promise.all([
                                fetch(`/api/absences/${selectedUser}`).then(r => r.json()),
                                fetch('/api/joursferies').then(r => r.json())
                            ])
                            .then(([events, joursFeriesData]) => {
                                // Les absences sont déjà formatées par le contrôleur
                                const holidays = joursFeriesData.map(holiday => ({
                                    title: holiday.nom,
                                    start: holiday.date,
                                    end: holiday.date,
                                    display: 'background',
                                    backgroundColor: '#4B5563',
                                    borderColor: '#374151',
                                    allDay: true,
                                    type: 'holiday',
                                    color: '#E5E7EB'
                                }));

                                // Combine les deux types d'événements
                                const allEvents = [...events, ...holidays];
                                console.log('Events:', allEvents); // Pour le débogage
                                successCallback(allEvents);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                failureCallback(error);
                            });
                    },
                    eventDidMount: function(info) {
                        if (info.event.extendedProps && info.event.extendedProps.type === 'absence') {
                            info.el.title = [
                                `Utilisateur: ${info.event.extendedProps.user}`,
                                `Motif: ${info.event.extendedProps.motif}`,
                                `Statut: ${info.event.extendedProps.status}`
                            ].join('\n');
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.extendedProps.type === 'absence' && info.event.extendedProps
                            .justificatif) {
                            window.location.href = `/absence/justificatif/${info.event.id}`;
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
