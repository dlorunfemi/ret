@extends('layouts.app', ['title' => 'Settings - Retrixnet'])

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
                                    <a href="{{ route('deposit.records') }}" class="list-group-item"><img
                                            src="{{ asset('img/deposit-records.png') }}" width="20" height="20"
                                            alt="Deposit"> Deposit Records</a>
                                    <a href="{{ route('withdrawal.records') }}" class="list-group-item"><img
                                            src="{{ asset('img/withdraw-record.png') }}" width="20" height="20"
                                            alt="withdraw records"> Withdrawal Records</a>
                                    <a href="{{ route('transfer.history') }}" class="list-group-item"><img
                                            src="{{ asset('img/transfer-history.png') }}" width="20" height="20"
                                            alt="Transfer History"> Transfer History</a>
                                    <a href="{{ route('settings') }}" class="list-group-item active"><i
                                            class="ri-settings-3-line me-1"></i> Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h4 class="fw-bold"><i class="ri-settings-3-line"></i> Settings</h4>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold">Preferences</h6>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="toggleBalanceVisibility">
                                            <label class="form-check-label" for="toggleBalanceVisibility">Hide balances by
                                                default</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="fw-bold">Security</h6>
                                        <a href="{{ route('password.request') }}" class="btn btn_blue_outline">Reset
                                            Password</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const key = 'balanceVisible';
            const toggle = document.getElementById('toggleBalanceVisibility');
            const current = localStorage.getItem(key);
            toggle.checked = current === 'false';
            toggle.addEventListener('change', () => {
                const visible = !toggle.checked; // if checked, hide balances
                localStorage.setItem(key, visible);
            });
        });
    </script>
@endpush
