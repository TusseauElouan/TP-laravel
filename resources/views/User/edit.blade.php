@extends('layouts.app')
@section('title', __('Edit a user'))

@section('content')

<div class="grid place-content-center text-center h-screen">
    <form method="POST" action="{{ route('user.update', $user) }}" class="flex flex-col border-gray-300 border-2 rounded-md space-y-6 p-10 w-80">
        @csrf
        @method('PUT')

        <div class="flex flex-col">
            <label for="nom" class="text-xl mx-1 mb-2">{{__('Last Name')}}</label>
            <input type="text" id="nom" name="nom" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" value="{{ old('nom', $user->nom) }}">
            @error('nom')
                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="prenom" class="text-xl mx-1 mb-2">{{__('First Name')}}</label>
            <input type="text" id="prenom" name="prenom" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" value="{{ old('prenom', $user->prenom) }}">
            @error('prenom')
                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <label for="email" class="text-xl mx-1 mb-2">{{__('Email')}}</label>
            <input type="text" id="email" name="email" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" value="{{ old('email', $user->email) }}">
            @error('email')
                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
            @enderror
        </div>

        <input type="submit" value="{{__('Edit')}}" class="bg-gray-900 rounded-md text-white py-2 cursor-pointer">
    </form>
</div>

@endsection
