@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Manage Permissions for {{ $user->name }}</h1>

    <form action="{{ route('admin.users.permissions.update', $user->id) }}" method="POST">
        @csrf
        @foreach($permissions as $permission)
            <div class="mb-2">
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                           {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                    {{ ucfirst($permission->name) }}
                </label>
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
    </form>
</div>
@endsection
