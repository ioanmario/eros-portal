@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">User Management</h1>

    <table class="table-auto w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Affiliate Code</th>
                <th class="border px-4 py-2">Permissions / Roles</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user->id }}</td>
                <td class="border px-4 py-2">{{ $user->name }}</td>
                <td class="border px-4 py-2">{{ $user->email }}</td>
                <td class="border px-4 py-2">{{ $user->affiliate_code ?? '-' }}</td>
                <td class="border px-4 py-2">
                    @if($user->roles->count() > 0)
                        <div><strong>Roles:</strong> {{ implode(', ', $user->getRoleNames()->toArray()) }}</div>
                    @endif
                    @if($user->permissions->count() > 0)
                        <div><strong>Permissions:</strong> {{ implode(', ', $user->getPermissionNames()->toArray()) }}</div>
                    @endif
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ route('admin.users.permissions.edit', $user->id) }}" class="text-blue-600">Manage Permissions</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
