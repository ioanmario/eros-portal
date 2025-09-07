@extends('layouts.app')

@section('content')
<div class="container py-5" x-data="mtForm()">
  <h2 class="mb-4">Link {{ strtoupper($platform) }}</h2>

  @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <form method="POST" action="{{ route('broker.sync.mt.verify', $platform) }}" class="card p-4 shadow-sm">
    @csrf

    <div class="mb-3 position-relative">
      <label class="form-label">Server <small class="text-muted">(exact MT {{ $platform === 'mt5' ? 5 : 4 }} server name)</small></label>
      <input name="server" x-model="server" class="form-control" autocomplete="off" placeholder="e.g. ICMarketsSC-Demo">
      <ul class="list-group position-absolute w-100" x-show="suggestions.length" style="z-index: 10;">
        <template x-for="item in suggestions" :key="item">
          <li class="list-group-item list-group-item-action" @click="choose(item)" x-text="item"></li>
        </template>
      </ul>
      @error('server') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Login</label>
      <input name="login" class="form-control" placeholder="Account number" value="{{ old('login') }}">
      @error('login') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" placeholder="Investor (read-only) password">
      @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
      <div class="form-text">Use your investor (read-only) password for safety.</div>
    </div>

    <button class="btn btn-primary w-100">Connect</button>
  </form>
</div>

{{-- tiny Alpine.js helper (or swap to your JS stack) --}}
<script>
function mtForm() {
  return {
    server: '',
    suggestions: [],
    timer: null,
    choose(v){ this.server = v; this.suggestions = []; },
    fetchServers(q) {
      fetch(`{{ route('broker.sync.servers.search') }}?version={{ $platform === 'mt5' ? 5 : 4 }}&q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(obj => {
          // obj is { "Broker A": ["Server-1","Server-2"], ... }
          const flat = [];
          Object.values(obj).forEach(list => list.forEach(s => flat.push(s)));
          this.suggestions = flat.slice(0,10);
        }).catch(()=>{ this.suggestions = []; });
    },
    init() {
      this.$watch('server', (v) => {
        clearTimeout(this.timer);
        if (!v || v.length < 2) { this.suggestions = []; return; }
        this.timer = setTimeout(() => this.fetchServers(v), 250);
      });
    }
  };
}
</script>
@endsection
