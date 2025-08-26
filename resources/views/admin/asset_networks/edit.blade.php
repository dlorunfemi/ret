@extends('layouts.admin', ['title' => 'Admin - Edit Asset Network'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Asset Network</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.asset_networks.update', $network) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Asset</label>
                                    <select class="form-select" name="asset_id" required>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}" @selected((int) old('asset_id', $network->asset_id) === $asset->id)>
                                                {{ $asset->symbol }} — {{ $asset->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Network Name</label>
                                    <input class="form-control" name="network_name"
                                        value="{{ old('network_name', $network->network_name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Deposit Address</label>
                                    <input class="form-control" name="deposit_address"
                                        value="{{ old('deposit_address', $network->deposit_address) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">QR Code Image</label>
                                    <input class="form-control" type="file" name="qr_file" accept="image/*">
                                    @if ($network->qr_path)
                                        <div class="form-text">Current: {{ $network->qr_path }}</div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Min Deposit</label>
                                    <input type="number" step="any" class="form-control" name="min_deposit"
                                        value="{{ old('min_deposit', $network->min_deposit) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Deposit Confirmations</label>
                                    <input type="number" class="form-control" name="deposit_confirmations"
                                        value="{{ old('deposit_confirmations', $network->deposit_confirmations) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Withdraw Confirmations</label>
                                    <input type="number" class="form-control" name="withdraw_confirmations"
                                        value="{{ old('withdraw_confirmations', $network->withdraw_confirmations) }}">
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="activeCheck" @checked(old('is_active', $network->is_active))>
                                        <label class="form-check-label" for="activeCheck">Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.asset_networks.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
