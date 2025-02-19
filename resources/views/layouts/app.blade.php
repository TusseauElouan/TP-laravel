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
        // Fonction pour appliquer le thème sombre ou clair selon la sauvegarde
        function applyThemeFromStorage() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const htmlElement = document.documentElement;

            if (savedTheme === 'dark') {
                htmlElement.classList.add('dark');
            } else {
                htmlElement.classList.remove('dark');
            }

            // Vérification et mise à jour des icônes si elles existent
            updateIcons(savedTheme);
        }

        // Fonction pour basculer entre Light et Dark Mode
        function toggleDarkMode() {
            const htmlElement = document.documentElement;
            const isDarkMode = htmlElement.classList.toggle('dark');

            // Sauvegarder la préférence utilisateur
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');

            // Mise à jour des icônes si elles existent
            updateIcons(isDarkMode ? 'dark' : 'light');
        }

        // Mise à jour des icônes (seulement si elles existent sur la page)
        function updateIcons(theme) {
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (sunIcon && moonIcon) {
                if (theme === 'dark') {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        }

        // Exécuter au chargement de chaque page
        document.addEventListener('DOMContentLoaded', applyThemeFromStorage);
    </script>
</head>

<body class="dark:bg-gray-700">
    <header class="bg-gray-900 dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('accueil') }}" class="text-white text-xl font-bold">
                    {{ __('Home') }}
                </a>

                <!-- Menu pour desktop -->
                <nav class="hidden md:flex items-center space-x-6">
                    @auth
                        <a href="{{ route('user.index') }}" class="text-white hover:text-gray-300 transition">
                            {{ Auth::user()->isA('admin') ? __('Users List') : __('Profile') }}
                        </a>
                        <a href="{{ route('absence.index') }}" class="text-white hover:text-gray-300 transition">
                            {{ __('Absences List') }}
                        </a>
                        <a href="{{ route('motif.index') }}" class="text-white hover:text-gray-300 transition">
                            {{ __('Reasons List') }}
                        </a>
                        <a href="{{ route('calendar.index') }}" class="text-white hover:text-gray-300 transition">
                            {{ __('Calendar') }}
                        </a>

                        <!-- Sélecteur stylisé pour les pages admin -->
                        @if (Auth::user()->isAn('admin'))
                            <div class="relative">
                                <select id="adminPages" class="bg-gray-700 text-white py-2 px-4 rounded-md border border-gray-600 focus:outline-none hover:bg-gray-600 transition">
                                    <option selected disabled>{{ __('Admin Pages') }}</option>
                                    <option value="{{ route('joursferies.index') }}">{{ __('Holidays Config') }}</option>
                                    <option value="{{ route('time-access.index') }}">{{ __('Time Access Config') }}</option>
                                    <option value="{{ route('planning-history.index') }}">{{ __('Planning History') }}</option>
                                    <option value="{{ route('role.index') }}">{{ __('Roles List') }}</option>
                                    <option value="{{ route('preferences.colors') }}">{{ __('Color Preferences') }}</option>
                                </select>
                            </div>
                        @endif
                    @endauth
                </nav>

                <!-- Sélecteur de Langue -->
                <div class="flex items-center space-x-4">
                    <!-- Sélecteur de langue -->
                    <form action="{{ route('langue.change') }}" method="GET">
                        <select name="lang" onchange="this.form.submit()"
                            class="bg-gray-700 text-white py-2 px-4 rounded-md border border-gray-600 focus:outline-none hover:bg-gray-600 transition">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                        </select>
                    </form>

                    <!-- Bouton Dark Mode -->
                    {{-- <button onclick="toggleDarkMode()" class="w-10 h-10 flex items-center justify-center bg-gray-700 text-white rounded-full transition-all hover:bg-gray-600">
                        <svg id="sunIcon" class="h-6 w-6 hidden dark:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v1m6.364 2.636l-.707.707M21 12h-1M17.364 19.364l-.707-.707M12 21v-1m-6.364-2.636l.707-.707M3 12h1M6.636 6.636l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="moonIcon" class="h-6 w-6 dark:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12.79A9 9 0 1111.21 3.05 7 7 0 0021 12.79z" />
                        </svg>
                    </button> --}}

                    <!-- Boutons Login/Logout -->
                    @guest
                        <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                            {{ __('Register') }}
                        </a>
                    @endguest
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    @endauth
                </div>

                <!-- Bouton Mobile -->
                <button id="mobileMenuButton" class="md:hidden text-white focus:outline-none">
                    ☰
                </button>
            </div>

            <!-- Menu mobile -->
            <div id="mobileMenu" class="hidden lg:hidden flex flex-col bg-gray-900 dark:bg-gray-800 space-y-2 py-4 px-6">
                @auth
                    <a href="{{ route('user.index') }}" class="text-white hover:text-gray-300 transition">
                        {{ Auth::user()->isA('admin') ? __('Users List') : __('Profile') }}
                    </a>
                    <a href="{{ route('absence.index') }}" class="text-white hover:text-gray-300 transition">
                        {{ __('Absences List') }}
                    </a>
                    <a href="{{ route('motif.index') }}" class="text-white hover:text-gray-300 transition">
                        {{ __('Reasons List') }}
                    </a>
                    <a href="{{ route('calendar.index') }}" class="text-white hover:text-gray-300 transition">
                        {{ __('Calendar') }}
                    </a>
                    @if (Auth::user()->isAn('admin'))
                        <div class="relative">
                            <select id="adminPagesMobile" class="w-full bg-gray-700 text-white py-2 px-3 rounded-md border border-gray-600 focus:outline-none hover:bg-gray-600 transition">
                                <option selected disabled>{{ __('Admin Pages') }}</option>
                                <option value="{{ route('joursferies.index') }}">{{ __('Holidays Config') }}</option>
                                <option value="{{ route('time-access.index') }}">{{ __('Time Access Config') }}</option>
                                <option value="{{ route('planning-history.index') }}">{{ __('Planning History') }}</option>
                                <option value="{{ route('role.index') }}">{{ __('Roles List') }}</option>
                                <option value="{{ route('preferences.colors') }}">{{ __('Color Preferences') }}</option>
                            </select>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <script>
        document.getElementById('mobileMenuButton').addEventListener('click', function () {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        // Redirection automatique lors du choix dans le select
        document.getElementById('adminPages')?.addEventListener('change', function () {
            window.location.href = this.value;
        });

        document.getElementById('adminPagesMobile')?.addEventListener('change', function () {
            window.location.href = this.value;
        });
    </script>


    <main>
        @yield('message')
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>
