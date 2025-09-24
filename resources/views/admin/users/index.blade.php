@extends('layouts.admin', ['title' => 'Admin - Users'])

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Users</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">New User</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Balances</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        @if ($user->balances && $user->balances->isNotEmpty())
                                            <div class="d-flex flex-column gap-1">
                                                @foreach ($user->balances->sortBy(fn($b) => $b->asset?->symbol) as $balance)
                                                    @php
                                                        $symbol = $balance->asset?->symbol;
                                                        $precision = $balance->asset?->precision ?? 8;
                                                        $amount = number_format(
                                                            (float) $balance->available,
                                                            $precision,
                                                            '.',
                                                            '',
                                                        );
                                                        $frozen = number_format(
                                                            (float) $balance->frozen,
                                                            $precision,
                                                            '.',
                                                            '',
                                                        );
                                                        $price =
                                                            $symbol && isset($prices[$symbol])
                                                                ? $prices[$symbol]
                                                                : null;
                                                        $value =
                                                            $price !== null
                                                                ? number_format(
                                                                    ((float) $balance->available) * ((float) $price),
                                                                    2,
                                                                    '.',
                                                                    ',',
                                                                )
                                                                : '-';
                                                    @endphp
                                                    <div class="small">
                                                        <strong>{{ $symbol }}</strong>:
                                                        <span>Amount: {{ $amount }}</span>,
                                                        <span>Value: {{ $value === '-' ? '-' : '$' . $value }}</span>,
                                                        <span>Frozen: {{ $frozen }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted small">No balances</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-2"
                                            data-balance-user-id="{{ $user->id }}">Balances</button>
                                        <button class="btn btn-sm btn-outline-secondary me-2"
                                            data-network-user-id="{{ $user->id }}">Network Settings</button>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete user?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Balances Modal -->
    <div class="modal fade" id="balancesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Balances</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="balancesForm">
                        <input type="hidden" id="balancesUserId" value="">
                        <div class="table-responsive">
                            <table class="table align-middle" id="balancesTable">
                                <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th>Available</th>
                                        <th>Frozen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBalancesBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Settings Modal -->
    <div class="modal fade" id="networkSettingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Network Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="networkSettingsForm">
                        <input type="hidden" id="networkUserId" value="">
                        <div class="table-responsive">
                            <table class="table align-middle" id="networkSettingsTable">
                                <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th>Network</th>
                                        <th>Min Deposit</th>
                                        <th>Deposit Confs</th>
                                        <th>Withdraw Confs</th>
                                        <th>Defaults</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveNetworkSettingsBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let currentUserId = null;
            let currentNetworkUserId = null;

            function openModal(userId) {
                currentUserId = userId;
                document.getElementById('balancesUserId').value = userId;
                const modalEl = document.getElementById('balancesModal');
                const modal = new bootstrap.Modal(modalEl);
                loadBalances(userId).then(() => modal.show());
            }

            async function loadBalances(userId) {
                const tbody = document.querySelector('#balancesTable tbody');
                tbody.innerHTML = '<tr><td colspan="3">Loading...</td></tr>';
                const res = await fetch(`/admin/users/${userId}/balances`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-danger">Failed to load balances</td></tr>';
                    return;
                }
                const json = await res.json();
                const rows = (json.data || []).map(item => {
                    const step = item.precision > 0 ?
                        `step="${'0.' + '0'.repeat(item.precision - 1) + '1'}"` : '';
                    return `
                        <tr data-asset-id="${item.asset_id}">
                            <td><strong>${item.asset_symbol}</strong> <small class="text-muted">${item.asset_name}</small></td>
                            <td style="max-width:180px"><input type="number" class="form-control form-control-sm" ${step} min="0" value="${item.available}"></td>
                            <td style="max-width:180px"><input type="number" class="form-control form-control-sm" ${step} min="0" value="${item.frozen}"></td>
                        </tr>
                    `;
                }).join('');
                tbody.innerHTML = rows || '<tr><td colspan="3" class="text-muted">No assets configured.</td></tr>';
            }

            async function save() {
                if (!currentUserId) return;
                const rows = Array.from(document.querySelectorAll('#balancesTable tbody tr[data-asset-id]'));
                const payload = {
                    balances: rows.map(tr => {
                        const inputs = tr.querySelectorAll('input');
                        return {
                            asset_id: parseInt(tr.getAttribute('data-asset-id')),
                            available: parseFloat(inputs[0].value || '0'),
                            frozen: parseFloat(inputs[1].value || '0')
                        };
                    })
                };

                const res = await fetch(`/admin/users/${currentUserId}/balances`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    const modalEl = document.getElementById('balancesModal');
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                    window.location.reload();
                } else {
                    alert('Failed to save balances');
                }
            }

            document.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-balance-user-id]');
                if (btn) {
                    e.preventDefault();
                    openModal(btn.getAttribute('data-balance-user-id'));
                }
                const nbtn = e.target.closest('[data-network-user-id]');
                if (nbtn) {
                    e.preventDefault();
                    openNetworkModal(nbtn.getAttribute('data-network-user-id'));
                }
            });
            document.getElementById('saveBalancesBtn').addEventListener('click', save);

            async function openNetworkModal(userId) {
                currentNetworkUserId = userId;
                document.getElementById('networkUserId').value = userId;
                const modalEl = document.getElementById('networkSettingsModal');
                const modal = new bootstrap.Modal(modalEl);
                await loadNetworkSettings(userId);
                modal.show();
            }

            async function loadNetworkSettings(userId) {
                const tbody = document.querySelector('#networkSettingsTable tbody');
                tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
                const res = await fetch(`/admin/users/${userId}/network-settings`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-danger">Failed to load settings</td></tr>';
                    return;
                }
                const json = await res.json();
                const rows = (json.data || []).map(item => {
                    const min = item.defaults.min_deposit ?? '';
                    const dep = item.defaults.deposit_confirmations ?? '';
                    const wdr = item.defaults.withdraw_confirmations ?? '';
                    return `
                        <tr data-network-id="${item.asset_network_id}">
                            <td><strong>${item.asset_symbol}</strong> <small class="text-muted">${item.asset_name}</small></td>
                            <td>${item.network_name}</td>
                            <td style="max-width:180px"><input type="number" class="form-control form-control-sm" step="any" min="0" value="${item.min_deposit ?? ''}" placeholder="${min}"></td>
                            <td style="max-width:160px"><input type="number" class="form-control form-control-sm" min="0" value="${item.deposit_confirmations ?? ''}" placeholder="${dep}"></td>
                            <td style="max-width:160px"><input type="number" class="form-control form-control-sm" min="0" value="${item.withdraw_confirmations ?? ''}" placeholder="${wdr}"></td>
                            <td class="small text-muted">min: ${min || '-'}, dep: ${dep || '-'}, wdr: ${wdr || '-'}</td>
                        </tr>
                    `;
                }).join('');
                tbody.innerHTML = rows || '<tr><td colspan="6" class="text-muted">No networks.</td></tr>';
            }

            document.getElementById('saveNetworkSettingsBtn').addEventListener('click', async () => {
                if (!currentNetworkUserId) return;
                const rows = Array.from(document.querySelectorAll(
                    '#networkSettingsTable tbody tr[data-network-id]'));
                const payload = {
                    network_settings: rows.map(tr => {
                        const inputs = tr.querySelectorAll('input');
                        const min = inputs[0].value;
                        const dep = inputs[1].value;
                        const wdr = inputs[2].value;
                        return {
                            asset_network_id: parseInt(tr.getAttribute('data-network-id')),
                            min_deposit: min === '' ? null : parseFloat(min),
                            deposit_confirmations: dep === '' ? null : parseInt(dep),
                            withdraw_confirmations: wdr === '' ? null : parseInt(wdr),
                        };
                    })
                };
                const res = await fetch(`/admin/users/${currentNetworkUserId}/network-settings`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                if (res.ok) {
                    const modalEl = document.getElementById('networkSettingsModal');
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                    window.location.reload();
                } else {
                    alert('Failed to save network settings');
                }
            });
        })();
    </script>
@endsection
