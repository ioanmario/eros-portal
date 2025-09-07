@extends('layouts.app')
@section('content')
<div class="container py-5">
  <h2 class="mb-3">Link Match-Trader</h2>
  <p>Match-Trader exposes a REST API (register/login/refresh, market data, positions, etc.). We’ll add a small login form here that calls their Login endpoint and stores the returned token to verify access.</p>
  <div class="alert alert-info">Coming next: add your broker URL & API key, then we’ll POST to the official Login endpoint and test a simple request like “Get Balance”.</div>
</div>
@endsection
