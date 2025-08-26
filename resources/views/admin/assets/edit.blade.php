@extends('layouts.admin', ['title' => 'Admin - Edit Asset'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Asset</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.assets.update', $asset) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Symbol</label>
                                <input class="form-control" name="symbol" value="{{ old('symbol', $asset->symbol) }}"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input class="form-control" name="name" value="{{ old('name', $asset->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precision</label>
                                <input type="number" class="form-control" name="precision"
                                    value="{{ old('precision', $asset->precision) }}" min="0" max="18"
                                    required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="activeCheck" @checked($asset->is_active)>
                                <label class="form-check-label" for="activeCheck">Active</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                                <a href="{{ route('admin.asset_networks.index') }}?asset_id={{ $asset->id }}"
                                    class="btn btn-outline-secondary">Manage Networks</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
