@extends('layouts.app', ['title' => 'Withdrawal - Retrixnet'])

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
                                    <a href="{{ route('withdrawal') }}" class="list-group-item active"><img
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
                                    <a href="{{ route('settings') }}" class="list-group-item"><img
                                            src="{{ asset('img/settings.png') }}" width="20" height="20"
                                            alt="settings"> Settings</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h4 class="fw-bold"><img src="{{ asset('img/withdraw.png') }}" width="50" height="50"
                                alt=""> Withdrawal</h4>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-7">
                                        <small class="fw-bold">Select coin/token and transfer mode</small>
                                        <div class="d-flex align-items-center mb-3 mt-3">
                                            <div
                                                class="step-number bg-gradient-blue text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3">
                                                1</div>
                                            <h6 class="mb-0 fw-bold text-dark">Select Coin/Token</h6>
                                        </div>
                                        <div class="custom-dropdown mb-4">
                                            <div class="form-control d-flex align-items-center justify-content-between"
                                                id="selectedCoin" style="cursor:pointer;">
                                                <span>Select Coin/Token</span>
                                                <span><i class="ri-arrow-down-s-line"></i></span>
                                            </div>
                                            <div class="dropdown-panel" id="coinDropdown">
                                                <input type="text" class="form-control mb-3"
                                                    placeholder="Search by coin/token" id="searchCoin">
                                                <div>
                                                    <small class="text-muted">History</small><br>
                                                    <span class="coin-badge">USDT</span>
                                                    <span class="coin-badge">BTC</span>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted">Popular Coins 🔥</small><br>
                                                    <span class="coin-badge">USDT</span>
                                                    <span class="coin-badge">ETH</span>
                                                    <span class="coin-badge">USDC</span>
                                                    <span class="coin-badge">BTC</span>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted">Crypto List</small>
                                                    <div id="coinList">
                                                        <div class="coin-item" data-coin="USDT"><img
                                                                src="{{ asset('img/icon/tether.png') }}" alt="">
                                                            USDT - Tether</div>
                                                        <div class="coin-item" data-coin="BTC"><img
                                                                src="{{ asset('img/icon/btc.png') }}" alt=""> BTC -
                                                            Bitcoin</div>
                                                        <div class="coin-item" data-coin="ETH"><img
                                                                src="{{ asset('img/icon/eth.png') }}" alt=""> ETH -
                                                            Ethereum</div>
                                                        <div class="coin-item" data-coin="USDC"><img
                                                                src="{{ asset('img/icon/usdc.png') }}" alt=""> USDC
                                                            - USD Coin</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4" id="networkSection">
                                            <div class="d-flex align-items-center mb-4">
                                                <div
                                                    class="step-number bg-gradient-blue text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3">
                                                    2</div>
                                                <h6 class="mb-0 fw-bold text-dark">Select Network</h6>
                                            </div>
                                            <div class="loader" id="networkLoader"><span></span><span></span><span></span>
                                            </div>
                                            <div class="row g-3" id="networkContainer"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <h6 class="fw-bold">Total Assets</h6>
                                        <p class="mb-1 text-muted small fw-bold">Available: <span
                                                id="assetAvailable">0</span> <span id="assetCoin">USDT</span></p>
                                        <p class="mb-3 text-muted small fw-bold">Frozen: <span id="assetFrozen">0</span>
                                            <span id="assetCoin2">USDT</span></p>
                                        <h6 class="fw-bold">Attention</h6>
                                        <p class="mb-1 small">Minimum deposit: <span class="fw-bold"
                                                id="minDeposit">0.086</span></p>
                                        <p class="mb-1 small">Deposit arrival: <span class="fw-bold" id="depositConf">12
                                                Confirmation(s)</span></p>
                                        <p class="mb-3 small">Withdrawal unlock: <span class="fw-bold"
                                                id="withdrawalConf">30 Confirmation(s)</span></p>
                                        <p class="my-auto text-muted small">Arrival time: Normal transfers are sent via
                                            crypto network, and the arrival time depends on the number of confirmations
                                            required by the recipient.</p>
                                        <h6 class="fw-bold mt-4 ">Enter Wallet Address</h6>
                                        <div id="walletsContainer" class="d-flex flex-column gap-3"></div>
                                    </div>
                                    <div class="loader" id="walletLoader" style="display:none;">
                                        <span></span><span></span><span></span>
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
                                                        <tr>
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
                                                                    <div class="col-auto"><img
                                                                            src="{{ asset('img/icon/tether.png') }}"
                                                                            width="25" height="25" alt="Tether">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">USDT</p><small
                                                                            class="text-muted">Tether</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="USDT">0</td>
                                                            <td class="val" data-asset="USDT">$0.00</td>
                                                            <td class="frozen" data-asset="USDT">0.00</td>
                                                            <td class="d-flex gap-2">
                                                                <a href="#"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="#"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto"><img
                                                                            src="{{ asset('img/icon/usdc.png') }}"
                                                                            width="25" height="25" alt="USDC">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">USDC</p><small
                                                                            class="text-muted">USDC</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="USDC">0</td>
                                                            <td class="val" data-asset="USDC">$0.00</td>
                                                            <td class="frozen" data-asset="USDC">0.00</td>
                                                            <td>
                                                                <a href="#"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="#"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto"><img
                                                                            src="{{ asset('img/icon/btc.png') }}"
                                                                            width="25" height="25" alt="BTC">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">BTC</p><small
                                                                            class="text-muted">BTC</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="BTC">0</td>
                                                            <td class="val" data-asset="BTC">$0.00</td>
                                                            <td class="frozen" data-asset="BTC">0.00</td>
                                                            <td>
                                                                <a href="#"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="#"
                                                                    class="badge text-bg-danger text-decoration-none px-3 py-2">Withdraw</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="col-auto"><img
                                                                            src="{{ asset('img/icon/eth.png') }}"
                                                                            width="25" height="25" alt="ETH">
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <p class="my-auto">ETH</p><small
                                                                            class="text-muted">ETH</small>
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td class="amt" data-asset="ETH">0</td>
                                                            <td class="val" data-asset="ETH">$0.00</td>
                                                            <td class="frozen" data-asset="ETH">0.00</td>
                                                            <td>
                                                                <a href="#"
                                                                    class="badge text-bg-success text-decoration-none px-3 py-2">Deposit</a>
                                                                <a href="#"
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
@endsection

@push('scripts')
    <script src="{{ asset('js/withdraw.js') }}"></script>
@endpush
