@extends('layouts.app', ['title' => 'Assets - Retrixnet'])

@section('content')
    @include('partials.navbar')

    <!-- Account Overview -->
    <div class="account_overview">
        <div class="container">
            <div class="col-lg-12 mx-auto">
                <div class="row g-3">
                    <div class="col-lg-3">
                        <div class="card border-0">
                            <div class="card-body p-0">
                                <div class="list-group">
                                    <a href="{{ route('assets') }}" class="list-group-item active"><img
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
                                    <a href="{{ route('settings') }}" class="list-group-item"><i
                                            class="ri-settings-3-line me-1"></i> Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h4 class="fw-bold"><img src="{{ asset('img/assets.png') }}" width="50" height="50"
                                alt=""> Assets</h4>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <p class="muted"><small>Est. Total Assets (USDT) <i class="ri-eye-line mx-2"
                                                    id="toggleVisibility"></i></small></p>
                                        <h2 class="hidden-balance fw-bold" id="balance">0.000 ≈ $0.00</h2>
                                        <div id="priceStatus" class="small text-muted"></div>
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

                        <div class="row g-3 mt-3 mb-5">
                            <div class="col-lg-12">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <small class="text-muted">All Assets</small>
                                                <table class="table align-middle">
                                                    <thead>
                                                        <tr id="row-USDT">
                                                            <th scope="col">Coin</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Value</th>
                                                            <th scope="col">Frozen</th>
                                                            <th scope="col">Operation</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto">
                                                                        <img src="{{ asset('img/icon/tether.png') }}"
                                                                            width="25" height="25" alt="Tether">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">USDT</p>
                                                                        <small class="text-muted">Tether</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="USDT">0</td>
                                                            <td class="val" data-asset="USDT">0.00 USD</td>
                                                            <td class="frozen" data-asset="USDT">0.00</td>
                                                            <td>
                                                                <a href="{{ route('deposit') }}"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="{{ route('withdrawal') }}"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr id="row-USDC">
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto">
                                                                        <img src="{{ asset('img/icon/usdc.png') }}"
                                                                            width="25" height="25" alt="USDC">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">USDC</p>
                                                                        <small class="text-muted">USDC</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="USDC">0</td>
                                                            <td class="val" data-asset="USDC">0.00 USD</td>
                                                            <td class="frozen" data-asset="USDC">0.00</td>
                                                            <td>
                                                                <a href="{{ route('deposit') }}"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="{{ route('withdrawal') }}"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr id="row-BTC">
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto">
                                                                        <img src="{{ asset('img/icon/btc.png') }}"
                                                                            width="25" height="25" alt="BTC">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">BTC</p>
                                                                        <small class="text-muted">BTC</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="BTC">0</td>
                                                            <td class="val" data-asset="BTC">0.00 USD</td>
                                                            <td class="frozen" data-asset="BTC">0.00</td>
                                                            <td>
                                                                <a href="{{ route('deposit') }}"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="{{ route('withdrawal') }}"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr id="row-ETH">
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto">
                                                                        <img src="{{ asset('img/icon/eth.png') }}"
                                                                            width="25" height="25" alt="ETH">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">ETH</p>
                                                                        <small class="text-muted">ETH</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="ETH">0</td>
                                                            <td class="val" data-asset="ETH">0.00 USD</td>
                                                            <td class="frozen" data-asset="ETH">0.00</td>
                                                            <td>
                                                                <a href="{{ route('deposit') }}"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="{{ route('withdrawal') }}"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
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
    <!-- Account Overview -->
@endsection

@push('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
