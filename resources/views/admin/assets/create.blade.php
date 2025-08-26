@extends('layouts.admin', ['title' => 'Admin - Create Asset'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">New Asset</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.assets.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Symbol</label>
                                <input class="form-control" name="symbol" value="{{ old('symbol') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precision</label>
                                <input type="number" class="form-control" name="precision"
                                    value="{{ old('precision', 8) }}" min="0" max="18" required>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="activeCheck" checked>
                                <label class="form-check-label" for="activeCheck">Active</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Create</button>
                                <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
