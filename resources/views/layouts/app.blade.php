<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    <title>@yield('title')</title>
    <script>
        // Applique la préférence utilisateur ou celle du système
        function applyThemeFromStorage() {
            const savedTheme = localStorage.getItem('theme');
            const htmlElement = document.documentElement;
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (savedTheme === 'dark') {
                htmlElement.classList.add('dark');
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                htmlElement.classList.remove('dark');
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Fonction de bascule entre les thèmes
        function toggleDarkMode() {
            const htmlElement = document.documentElement;
            const isDarkMode = htmlElement.classList.toggle('dark');
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            // Sauvegarder la préférence utilisateur
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');

            // Mettre à jour les icônes
            if (isDarkMode) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Initialisation au chargement de la page
        window.addEventListener('load', applyThemeFromStorage);
    </script>


</head>

<body class="dark:bg-gray-700">
    <header>
        <div class="flex text-center bg-gray-900 flex-wrap font-bold tracking-wide items-center">
            <a href="{{ route('accueil') }}"
                class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">{{ __('Home') }}</a>
            @auth
                <a href="{{ route('user.index') }}"
                    class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">
                    @if (Auth::user()->isA('admin'))
                        {{ __('Users List') }}
                    @else
                        {{ __('Profile') }}
                    @endif

                </a>
                <a href="{{ route('absence.index') }}"
                    class="p-3 my-2 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{ __('Absences List') }}</a>
                <a href="{{ route('motif.index') }}"
                    class="p-3 my-2 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{ __('Reasons List') }}</a>
                <a href="{{ route('calendar.index') }}"
                    class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{ __('Calendar') }}</a>
                @if (Auth::user()->isAn('admin'))
                    <a href="{{ route('joursferies.index') }}"
                        class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">
                        Configuration des jours fériés
                    </a>
                    <a href="{{ route('time-access.index') }}" class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">
                        Gestion des accès horaires
                    </a>
                @endif
            <a href="{{ route('preferences.colors') }}" class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">
                {{ __('Préférences de couleurs') }}
            </a>
            @if (auth()->user()->isA('admin'))
                <a href="{{ route('role.index') }}" class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{__('Roles List')}}</a>
            @endif
            @endauth

            <div class="ml-auto flex items-center mr-4 space-x-3">
                <form action="{{ route('langue.change') }}" method="GET">
                    <select name="lang" onchange="this.form.submit()"

                        class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                        <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                    </select>
                </form>
                <button onclick="toggleDarkMode()"
                class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white font-semibold rounded-full shadow-md hover:bg-blue-600 transition-all duration-300 ease-in-out transform hover:scale-105">
                <svg id="sunIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m6.364 2.636l-.707.707M21 12h-1M17.364 19.364l-.707-.707M12 21v-1m-6.364-2.636l.707-.707M3 12h1M6.636 6.636l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg id="moonIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12.79A9 9 0 1111.21 3.05 7 7 0 0021 12.79z" />
                </svg>
            </button>
            </div>
            @guest
                <a href="{{ route('login') }}"
                    class="my-2 p-3 bg-blue-500 text-white hover:bg-blue-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">{{ __('Log in') }}</a>
                <a href="{{ route('register') }}"
                    class="ml-2 my-2 p-3 bg-green-500 text-white hover:bg-blue-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">{{ __('Register') }}</a>
            @endguest
            @auth
                <form method="POST" action="{{ route('logout') }}" class="mr-3">
                    @csrf
                    <button type="submit"
                        class="my-2 p-3 bg-red-500 text-white hover:bg-red-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">
                        {{ __('Log Out') }}
                    </button>
                </form>
                <div class="bg-white rounded-md p-2 mr-3">
                    <p class="text-gray-900 font-bold">{{ Auth::user()->initiales }}</p>
                </div>
            @endauth
        </div>
    </header>

    <main>
        @yield('message')
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
