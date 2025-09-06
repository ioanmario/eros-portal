{{-- Always visible for everyone --}}
<li><a href="{{ url('/dashboard') }}">Dashboard</a></li>

{{-- Normal user menus --}}
@role('user')
    @can('view account management')
        <li><a href="{{ url('/account') }}">Account Management</a></li>
    @endcan

    @can('view roadmap')
        <li><a href="{{ url('/roadmap') }}">Roadmap</a></li>
    @endcan

    @can('view affiliate')
        <li><a href="{{ url('/affiliate') }}">Affiliate</a></li>
    @endcan

    @can('view expert advisors')
        <li><a href="{{ url('/experts') }}">Expert Advisors</a></li>
    @endcan

    @can('view support')
        <li><a href="{{ url('/support') }}">Support</a></li>
    @endcan
@endrole

{{-- Admin-only menus --}}
@role('admin')
    <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
    <li><a href="{{ route('admin.users.index') }}">Manage Users</a></li>
    <li><a href="{{ route('admin.users.index') }}">Manage Permissions</a></li>
@endrole
