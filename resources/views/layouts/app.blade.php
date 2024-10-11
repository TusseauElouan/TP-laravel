<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>@yield('title')</title>
</head>

<body>
    <header>
        <div class="flex text-center bg-gray-900 flex-wrap font-bold tracking-wide items-center">
            <a href="{{ route('accueil') }}" class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">{{__('Home')}}</a>
            @auth
            <a href="{{ route('user.index') }}" class="my-2 p-3 hover:bg-gray-700 text-white ease-in duration-300 border-x border-white">
                @if(Auth::user()->isA('admin'))
                    {{__('Users List')}}
                @else
                    {{__('Profile')}}
                @endif
            </a>
            <a href="{{ route('absence.index') }}" class="p-3 my-2 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{__('Absences List')}}</a>
            <a href="{{ route('motif.index') }}" class="p-3 my-2 hover:bg-gray-700 text-white ease-in duration-300 border-r border-white">{{__('Reasons List')}}</a>
            @endauth
            <form action="{{ route('langue.change') }}" method="GET" class="ml-auto mr-4">
                <select name="lang" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                </select>
            </form>
            @guest
                <a href="{{ route('login') }}" class="my-2 p-3 bg-blue-500 text-white hover:bg-blue-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">{{__('Log in')}}</a>
                <a href="{{ route('register') }}" class="ml-2 my-2 p-3 bg-green-500 text-white hover:bg-blue-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">{{__('Register')}}</a>
            @endguest

            @auth
                <form method="POST" action="{{ route('logout') }}" class="mr-3">
                    @csrf
                    <button type="submit" class="my-2 p-3 bg-red-500 text-white hover:bg-red-600 rounded-md ease-in duration-300 shadow-md hover:shadow-lg">
                        {{__('Log Out')}}
                    </button>
                </form>
                <div class="bg-white rounded-md p-2 mr-3">
                    <p class="text-gray-900 font-bold" >{{Auth::user()->initiales}}</p>
                </div>
            @endauth
        </div>
    </header>

    <main>
        @yield('message')
        @yield('content')
    </main>

</body>
</html>
