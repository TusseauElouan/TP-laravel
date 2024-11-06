@extends('layouts.app')

@section('title', __('know more about reasons'))

@section('content')
@if(session('success') || session('error'))
<div id="alert-message" class="flex justify-between items-center w-full max-w-lg mx-auto mt-6 p-4 mb-6 text-sm text-white rounded-lg shadow-lg @if(session('success')) bg-green-500 @else bg-red-500 @endif">
    <span>
        {{ session('success') ?? session('error') }}
    </span>
    <button type="button" class="ml-4 font-bold text-xl" onclick="document.getElementById('alert-message').remove();">
        &times;
    </button>
</div>
@endif
@auth
<h1 class="font-bold text-center text-5xl m-4">{{__('know more about reasons')}}</h1>


@endauth
@endsection
