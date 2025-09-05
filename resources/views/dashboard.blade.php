@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome to your Dashboard</h1>

    <ul>
        <li><a href="{{ route('account') }}">Account Management</a></li>
        <li><a href="{{ route('roadmap') }}">Roadmap</a></li>
        <li><a href="{{ route('affiliate') }}">Affiliate</a></li>
        <li><a href="{{ route('expert.advisors') }}">Expert Advisors</a></li>
        <li><a href="{{ route('support') }}">Support</a></li>
    </ul>
</div>
@endsection
