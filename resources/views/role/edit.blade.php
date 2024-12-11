@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le rôle : {{ $role->title }}</h1>
    <form action="{{ route('role.update', $role) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nom du rôle -->
        <div class="mb-3">
            <label for="name" class="form-label">Nom du rôle</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
        </div>

        <!-- Titre du rôle -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre du rôle</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $role->title) }}">
        </div>

        <!-- Permissions -->
        <div class="mb-3">
            <label class="form-label">Permissions</label>
            <div class="row">
                @foreach ($abilities as $ability)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="ability-{{ $ability->id }}"
                                   name="abilities[]" value="{{ $ability->name }}"
                                   {{ in_array($ability->name, $roleAbilities ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ability-{{ $ability->id }}">
                                {{ $ability->title ?? ucfirst($ability->name) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Boutons -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
