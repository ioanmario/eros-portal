@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Admin Panel</h1>

    <ul class="list-disc pl-5">
        <li><a href="{{ route('admin.users.index') }}" class="text-blue-600">Manage Users</a></li>
        <li><a href="{{ route('admin.users.index') }}" class="text-blue-600">Manage Permissions</a></li>
    </ul>
</div>
@endsection
