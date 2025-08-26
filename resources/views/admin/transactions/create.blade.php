@extends('layouts.admin', ['title' => 'Admin - Create Transaction'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">New Transaction</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.transactions.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">User</label>
                                    <select class="form-select" name="user_id" required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected((int) old('user_id') === $user->id)>
                                                {{ $user->name ? $user->name . ' (' . $user->email . ')' : $user->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Asset</label>
                                    <select class="form-select" name="asset_id" required>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}" @selected((int) old('asset_id') === $asset->id)>
                                                {{ $asset->symbol }} — {{ $asset->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Network (optional)</label>
                                    <select class="form-select" name="asset_network_id">
                                        <option value="" @selected(old('asset_network_id') === null || old('asset_network_id') === '')>None</option>
                                        @foreach ($networks as $network)
                                            <option value="{{ $network->id }}" @selected((int) old('asset_network_id') === $network->id)>
                                                {{ $network->network_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" name="type" required>
                                        <option value="deposit" @selected(old('type') === 'deposit')>Deposit</option>
                                        <option value="withdrawal" @selected(old('type') === 'withdrawal')>Withdrawal</option>
                                        <option value="adjustment" @selected(old('type') === 'adjustment')>Adjustment</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount</label>
                                    <input type="number" step="any" class="form-control" name="amount"
                                        value="{{ old('amount') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fee</label>
                                    <input type="number" step="any" class="form-control" name="fee"
                                        value="{{ old('fee', 0) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Address (optional)</label>
                                    <input class="form-control" name="address" value="{{ old('address') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                        <option value="approved" @selected(old('status') === 'approved')>Approved</option>
                                        <option value="rejected" @selected(old('status') === 'rejected')>Rejected</option>
                                        <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tx Hash (optional)</label>
                                    <input class="form-control" name="tx_hash" value="{{ old('tx_hash') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmed At (optional)</label>
                                    <input type="datetime-local" class="form-control" name="confirmed_at"
                                        value="{{ old('confirmed_at') }}">
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-primary">Create</button>
                                <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
