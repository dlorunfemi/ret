@extends('layouts.admin', ['title' => 'Admin - Create Balance'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">New Balance</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.balances.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">User ID</label>
                                <input class="form-control" name="user_id" value="{{ old('user_id') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Asset ID</label>
                                <input class="form-control" name="asset_id" value="{{ old('asset_id') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Available</label>
                                <input type="number" step="any" class="form-control" name="available"
                                    value="{{ old('available', 0) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Frozen</label>
                                <input type="number" step="any" class="form-control" name="frozen"
                                    value="{{ old('frozen', 0) }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Create</button>
                                <a href="{{ route('admin.balances.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
