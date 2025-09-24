@extends('layouts.app', ['title' => 'Deposit Records - Retrixnet'])

@section('content')
    @include('partials.navbar')

    <div class="account_overview">
        <div class="container">
            <div class="col-lg-12 mx-auto">
                <div class="row g-3">
                    <div class="col-lg-3">
                        <div class="card border-0">
                            <div class="card-body p-0">
                                <div class="list-group">
                                    <a href="{{ route('assets') }}" class="list-group-item"><img
                                            src="{{ asset('img/assets.png') }}" width="20" height="20"
                                            alt="Assets"> Assets</a>
                                    <a href="{{ route('deposit') }}" class="list-group-item"><img
                                            src="{{ asset('img/deposit.png') }}" width="20" height="20"
                                            alt="Deposit"> Deposit</a>
                                    <a href="{{ route('withdrawal') }}" class="list-group-item"><img
                                            src="{{ asset('img/withdraw.png') }}" width="20" height="20"
                                            alt="Withdrawal"> Withdrawal</a>
                                    <a href="{{ route('deposit.records') }}" class="list-group-item active"><img
                                            src="{{ asset('img/deposit-records.png') }}" width="20" height="20"
                                            alt="Deposit"> Deposit Records</a>
                                    <a href="{{ route('withdrawal.records') }}" class="list-group-item"><img
                                            src="{{ asset('img/withdraw-record.png') }}" width="20" height="20"
                                            alt="withdraw records"> Withdrawal Records</a>
                                    <a href="{{ route('transfer.history') }}" class="list-group-item"><img
                                            src="{{ asset('img/transfer-history.png') }}" width="20" height="20"
                                            alt="Transfer History"> Transfer History</a>
                                    <a href="{{ route('settings') }}" class="list-group-item"><img
                                            src="{{ asset('img/settings.png') }}" width="20" height="20"
                                            alt="settings"> Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h4 class="fw-bold"><img src="{{ asset('img/deposit-records.png') }}" width="50" height="50"
                                alt=""> Deposit Records</h4>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <p class="muted"><small>Est. Total Assets (USDT) <i class="ri-eye-line mx-2"
                                                    id="toggleVisibility"></i></small></p>
                                        <h4 class="hidden-balance fw-bold" id="balance">0.000 ≈ 0.00 USD</h4>
                                    </div>
                                    <div class="col-lg-5 m-auto">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('deposit') }}" class="btn btn_red"><i
                                                    class="ri-corner-down-right-fill"></i> Deposit Crypto</a>
                                            <a href="{{ route('withdrawal') }}" class="btn btn_blue_outline"><i
                                                    class="ri-corner-down-left-fill"></i> Withdraw Crypto</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-5">
                            <div class="col-lg-12">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <small class="text-muted">All Deposits</small>
                                                <table class="table align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Coin</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Status</th>
                                                            <th scope="col">Network</th>
                                                            <th scope="col">Tx Hash</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="depositsBody">
                                                        <tr id="dep-empty">
                                                            <td colspan="5" class="text-muted">No deposits yet.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        async function hydrateDeposits() {
            const res = await fetch('/api/transactions', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) return;
            const data = await res.json();
            const tbody = document.getElementById('depositsBody');
            tbody.innerHTML = '';
            (data.data || []).filter(t => t.type === 'deposit').forEach(t => {
                const tr = document.createElement('tr');
                tr.innerHTML =
                    `<td>${t.asset_symbol ?? t.asset_id}</td><td>${t.amount}</td><td>${t.status}</td><td>${t.network_name ?? ''}</td><td>${t.tx_hash ?? ''}</td>`;
                tbody.appendChild(tr);
            });
            if (!tbody.children.length) {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td colspan="5" class="text-muted">No deposits yet.</td>';
                tbody.appendChild(tr);
            }
        }
        document.addEventListener('DOMContentLoaded', hydrateDeposits);
    </script>
@endpush
