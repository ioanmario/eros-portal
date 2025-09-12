@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row g-4 align-items-stretch">
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h4 mb-2">Account Management</h2>
                    <p class="text-muted mb-4">Connect your broker to enable auto-sync and analytics.</p>

                    <!-- Broker Selection with logos -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Platform</label>
                        <select class="form-select" id="platform" name="platform">
                            @foreach($brokers as $b)
                                <option value="{{ $b['key'] }}">{{ $b['name'] }}</option>
                            @endforeach
                        </select>
                        <div class="d-flex gap-3 align-items-center mt-2">
                            @foreach($brokers as $b)
                                <img src="{{ $b['logo'] }}" alt="{{ $b['name'] }}" height="24">
                            @endforeach
                        </div>
                    </div>

                    <!-- Broker Login Form -->
                    <form id="brokerSyncForm">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Server</label>
                                <input type="text" name="server" class="form-control" placeholder="e.g. MetaQuotes-Demo" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Login</label>
                                <input type="text" name="login" class="form-control" placeholder="Your trading account login" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Investor Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Read-only investor password" required>
                            </div>
                        </div>

                        <button id="connectBtn" type="submit" class="btn btn-primary w-100 mt-3">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="connectSpinner" role="status" aria-hidden="true"></span>
                            <span id="connectLabel">Connect</span>
                        </button>
                    </form>

                    <div id="brokerSyncResult" class="mt-3"></div>
                    <div class="mt-3">
                        <div class="row g-2">
                            <div class="col-auto">
                                <span class="badge bg-primary">Plan: {{ ucfirst($plan) }}</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-secondary">Accounts: {{ $currentCount }}/{{ $maxAccounts }}</span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-success">Remaining: {{ $remaining }}</span>
                            </div>
                            @if($plan === 'free')
                            <div class="col-auto">
                                <a href="{{ route('plans') }}" class="btn btn-brand btn-sm">Upgrade Plan</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h3 class="h5 mb-3">💡 Tips</h3>
                    <div class="mb-0">
                        <div class="alert alert-info mb-2">
                            <strong>Server Name:</strong> Use the exact name from your MetaTrader terminal.
                        </div>
                        <div class="alert alert-warning mb-2">
                            <strong>Password:</strong> Only investor (read-only) password is required.
                        </div>
                        <div class="alert alert-secondary mb-0">
                            <strong>Platforms:</strong> cTrader & MatchTrader save without verification.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-3">Your Linked Accounts</h3>
                    <div id="accountsList" class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th class="fw-semibold">Platform</th>
                                    <th class="fw-semibold">Server</th>
                                    <th class="fw-semibold">Login</th>
                                    <th class="fw-semibold">Label</th>
                                    <th class="fw-semibold">Added</th>
                                    <th class="fw-semibold text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="accountsTbody">
                                <tr><td colspan="6" class="text-center text-muted py-4">Loading accounts...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("brokerSyncForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const platform = document.getElementById("platform").value;

    // All flows handled here; form saves account and (for MT) attempts verification

    const verifyIfMt = (platform === 'mt4' || platform === 'mt5');
    const verifyUrl = verifyIfMt ? `/broker-sync/${platform}/verify` : null;

    const doVerify = verifyIfMt ? fetch(verifyUrl, {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    }) : Promise.resolve({ json: async () => ({ success: true, message: 'Saved without verification.' }) });
    doVerify.then(async res => {
        const resultBox = document.getElementById("brokerSyncResult");
        // Try JSON first; if it fails, fallback to text so we can show server errors
        try { return await res.json(); } catch (_) {
            const text = await res.text();
            resultBox.innerHTML = `<div class="alert alert-danger">${text.substring(0, 500)}</div>`;
            throw new Error('Non-JSON response');
        }
    })
    .then(data => {
        const resultBox = document.getElementById("brokerSyncResult");
        // Save account regardless of verification success; you might choose to only save on success
        const payload = new FormData();
        payload.append('platform', platform);
        payload.append('server', formData.get('server'));
        payload.append('login', formData.get('login'));
        payload.append('password', formData.get('password'));
        fetch('/broker-accounts', {
            method: 'POST',
            body: payload,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(save => {
            const suffix = data.success ? '' : ' (verification failed)';
            if (save && save.success) {
                resultBox.innerHTML = `<div class="alert alert-success">Saved account${suffix}. ${data.message || ''}</div>`;
            } else {
                resultBox.innerHTML = `<div class="alert alert-warning">Could not save account. ${data.message || ''}</div>`;
            }
        }).catch(() => {
            resultBox.innerHTML = `<div class="alert alert-warning">Verification completed, but saving failed.</div>`;
        });
    })
    .catch(err => {
        document.getElementById("brokerSyncResult").innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
    })
    .finally(() => {
        document.getElementById('connectSpinner').classList.add('d-none');
        document.getElementById('connectLabel').textContent = 'Connect';
        document.getElementById('connectBtn').disabled = false;
    });
});

// Toggle loading state on submit
document.getElementById('brokerSyncForm').addEventListener('submit', function() {
    document.getElementById('connectSpinner').classList.remove('d-none');
    document.getElementById('connectLabel').textContent = 'Connecting...';
    document.getElementById('connectBtn').disabled = true;
});

// Fetch and render accounts
function fetchAccounts(){
    fetch('/broker-accounts', { headers: { 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(rows => {
            const tb = document.getElementById('accountsTbody');
            if (!rows.length) { tb.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No accounts linked yet. Add one above!</td></tr>'; return; }
            tb.innerHTML = rows.map(a => `
                <tr>
                    <td>
                        <span class="badge bg-primary text-uppercase">${a.platform}</span>
                    </td>
                    <td>
                        <code class="text-muted">${a.server || 'N/A'}</code>
                    </td>
                    <td>
                        <strong>${a.login}</strong>
                    </td>
                    <td>
                        <span class="text-muted">${a.label || '—'}</span>
                    </td>
                    <td>
                        <small class="text-muted">${new Date(a.created_at).toLocaleDateString()}</small>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAccount(${a.id})" title="Remove account">
                            🗑️ Remove
                        </button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(() => {
            document.getElementById('accountsTbody').innerHTML = '<tr><td colspan="6" class="text-center text-danger py-4">❌ Failed to load accounts. Please refresh the page.</td></tr>';
        });
}

function deleteAccount(id){
    if (!confirm('Remove this account?')) return;
    fetch(`/broker-accounts/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(() => fetchAccounts())
    .catch(() => alert('Failed to delete.'));
}

document.addEventListener('DOMContentLoaded', fetchAccounts);
</script>
@endsection
