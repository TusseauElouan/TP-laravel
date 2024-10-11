@extends('layouts.app')
@section('title', __('Add a reason'))

@section('content')
<div class="grid place-content-center text-center h-screen">
    <form method="POST" action="{{ route('motif.store') }}" class="flex flex-col border-gray-300 border-2 rounded-md space-y-6 p-10 w-80">
        @csrf

        <!-- Nom du motif -->
        <div class="flex flex-col">
            <label for="libelle" class="text-xl mx-1 mb-2">{{__('Reason Name')}}</label>
            <input type="text" id="libelle" name="libelle" class="border-gray-300 border-2 rounded-md p-2 text-gray-900 focus:ring-blue-500 focus:border-blue-500 focus:outline-none" value="{{ old('libelle') }}">
            @error('libelle')
                <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
            @enderror
        </div>

        <!-- Checkbox Accessible aux salariés -->
        <div class="flex items-center justify-center space-x-2">
            <input type="checkbox" name="is_accessible_salarie" id="is_accessible_salarie" class="w-6 h-6 text-indigo-600 bg-gray-200 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 focus:ring-offset-2 focus:outline-none focus:ring-opacity-50" {{ old('is_accessible_salarie') ? 'checked' : '' }}>
            <label for="is_accessible_salarie" class="text-lg">{{__('Accessible to employee')}}</label>
        </div>

        <input type="submit" value="{{__('Add')}}" class="bg-gray-900 rounded-md text-white py-2 cursor-pointer">
    </form>
</div>
@endsection
