@extends('layouts.app')
@section('content')
<div class="container py-5">
  <h2 class="mb-3">Link cTrader</h2>
  <p>cTrader uses OAuth 2.0 — you don’t type your login/password here. Click below to authenticate with your cTID and grant access, then we’ll receive an authorization code and exchange it for an access token.</p>
  <a class="btn btn-primary" href="#">Continue with cTrader</a>
  <p class="text-muted mt-2">Docs: cTrader Open API authentication flow.</p>
</div>
@endsection
