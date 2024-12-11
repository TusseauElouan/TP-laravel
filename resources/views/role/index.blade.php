@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{__('Roles List')}}</h1>
    <a href="{{ route('role.create') }}" class="btn btn-success mb-3">{{__('Roles Create')}}</a>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>{{__('Name')}}</th>
                <th>{{__('title')}}</th>
                <th>{{__('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->title }}</td>
                <td>
                    <form action="{{ route('role.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');">{{__('Delete')}}</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
