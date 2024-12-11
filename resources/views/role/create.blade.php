@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{__('Roles Create')}}</h1>
    <form action="{{ route('role.store') }}" method="POST" class="shadow p-4 rounded bg-light">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">{{__('Roles Name')}}</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">{{__('Roles Title')}}</label>
            <input type="text" id="title" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">{{__('Permissions')}}</label>
            <div class="form-check">
                @foreach($abilities as $ability)
                <div>
                    <input type="checkbox" id="ability-{{ $ability->id }}" name="abilities[]" value="{{ $ability->name }}">
                    <label for="ability-{{ $ability->id }}">{{ $ability->title ?? $ability->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
    </form>
</div>
@endsection
