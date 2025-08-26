@extends('layouts.admin', ['title' => 'Admin - Assets'])

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Assets</h5>
            <a href="{{ route('admin.assets.create') }}" class="btn btn-primary btn-sm">New Asset</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Name</th>
                                <th>Precision</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                                <tr>
                                    <td>{{ $asset->symbol }}</td>
                                    <td>{{ $asset->name }}</td>
                                    <td>{{ $asset->precision }}</td>
                                    <td>{{ $asset->is_active ? 'Yes' : 'No' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.assets.edit', $asset) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.assets.destroy', $asset) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete asset?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">No assets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $assets->links() }}
            </div>
        </div>
    </div>
@endsection
