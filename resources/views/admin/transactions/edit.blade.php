@extends('layouts.admin', ['title' => 'Admin - Edit Transaction'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Transaction #{{ $transaction->id }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">User</label>
                                    <select class="form-select" name="user_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected((int) old('user_id', $transaction->user_id) === $user->id)>
                                                {{ $user->name ? $user->name . ' (' . $user->email . ')' : $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label>
                                    <select class="form-select" name="asset_id">
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}" @selected((int) old('asset_id', $transaction->asset_id) === $asset->id)>
                                                {{ $asset->symbol }} — {{ $asset->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Network</label>
                                    <select class="form-select" name="asset_network_id">
                                        <option value="">None</option>
                                        @foreach ($networks as $network)
                                            <option value="{{ $network->id }}" @selected((int) old('asset_network_id', $transaction->asset_network_id) === $network->id)>
                                                {{ $network->network_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type">
                                        @foreach (['deposit', 'withdrawal', 'adjustment'] as $type)
                                            <option value="{{ $type }}" @selected(old('type', $transaction->type) === $type)>
                                                {{ ucfirst($type) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount</label>
                                    <input type="number" step="any" class="form-control" name="amount"
                                        value="{{ old('amount', $transaction->amount) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fee</label>
                                    <input type="number" step="any" class="form-control" name="fee"
                                        value="{{ old('fee', $transaction->fee) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input class="form-control" name="address"
                                        value="{{ old('address', $transaction->address) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        @foreach (['pending', 'approved', 'rejected', 'completed'] as $status)
                                            <option value="{{ $status }}" @selected(old('status', $transaction->status) === $status)>
                                                {{ ucfirst($status) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tx Hash</label>
                                    <input class="form-control" name="tx_hash"
                                        value="{{ old('tx_hash', $transaction->tx_hash) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmed At</label>
                                    <input type="datetime-local" class="form-control" name="confirmed_at"
                                        value="{{ old('confirmed_at', optional($transaction->confirmed_at)->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
