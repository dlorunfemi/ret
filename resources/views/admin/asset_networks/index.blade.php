@extends('layouts.admin', ['title' => 'Admin - Asset Networks'])

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Asset Networks</h5>
            <a href="{{ route('admin.asset_networks.create') }}" class="btn btn-primary btn-sm">New Network</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Asset</th>
                                <th>Network</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($networks as $network)
                                <tr>
                                    <td>{{ $network->asset?->symbol }} — {{ $network->asset?->name }}</td>
                                    <td>{{ $network->network_name }}</td>
                                    <td>{{ $network->is_active ? 'Yes' : 'No' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.asset_networks.edit', $network) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.asset_networks.destroy', $network) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete network?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No networks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $networks->links() }}
            </div>
        </div>
    </div>
@endsection
