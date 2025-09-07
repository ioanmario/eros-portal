@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">Broker Sync</h2>

  <div class="row g-3">
    <div class="col-12 col-md-6 col-lg-4">
      <a class="btn btn-outline-primary w-100 py-3" href="{{ route('broker.sync.mt.form','mt5') }}">
        MetaTrader 5
      </a>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <a class="btn btn-outline-primary w-100 py-3" href="{{ route('broker.sync.mt.form','mt4') }}">
        MetaTrader 4
      </a>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <a class="btn btn-outline-secondary w-100 py-3" href="{{ route('broker.sync.ctrader') }}">
        cTrader (OAuth)
      </a>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
      <a class="btn btn-outline-secondary w-100 py-3" href="{{ route('broker.sync.matchtrader') }}">
        Match-Trader
      </a>
    </div>
  </div>
</div>
@endsection
