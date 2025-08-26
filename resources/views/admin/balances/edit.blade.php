@extends('layouts.admin', ['title' => 'Admin - Edit Balance'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Balance</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.balances.update', $balance) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Available</label>
                                <input type="number" step="any" class="form-control" name="available"
                                    value="{{ old('available', $balance->available) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Frozen</label>
                                <input type="number" step="any" class="form-control" name="frozen"
                                    value="{{ old('frozen', $balance->frozen) }}">
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.balances.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
