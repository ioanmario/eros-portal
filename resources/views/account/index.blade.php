@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Account Management</h2>
    <p>Welcome to your account settings. Connect your broker account below.</p>

    <!-- Broker Selection -->
    <div class="mb-4">
        <label class="form-label fw-bold">Select Broker</label>
        <select class="form-select" id="platform" name="platform">
            @foreach($brokers as $broker)
                <option value="{{ strtolower($broker) }}">{{ $broker }}</option>
            @endforeach
        </select>
    </div>

    <!-- Broker Login Form -->
    <form id="brokerSyncForm">
        @csrf

        <div class="mb-3">
            <label class="form-label">Server</label>
            <input type="text" name="server" class="form-control" placeholder="e.g. MetaQuotes-Demo" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Login</label>
            <input type="text" name="login" class="form-control" placeholder="Your trading account login" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Your investor password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Connect</button>
    </form>

    <div id="brokerSyncResult" class="mt-3"></div>
</div>

<script>
document.getElementById("brokerSyncForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const platform = document.getElementById("platform").value;

    fetch(`/broker-sync/${platform}/verify`, {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(res => res.json())
    .then(data => {
        const resultBox = document.getElementById("brokerSyncResult");
        if (data.success) {
            resultBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        } else {
            resultBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(err => {
        document.getElementById("brokerSyncResult").innerHTML =
            `<div class="alert alert-danger">Error: ${err.message}</div>`;
    });
});
</script>
@endsection
