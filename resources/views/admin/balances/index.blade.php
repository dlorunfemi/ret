@extends('layouts.admin', ['title' => 'Admin - Balances'])

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Balances</h5>
            <a href="{{ route('admin.balances.create') }}" class="btn btn-primary btn-sm">New Balance</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Asset</th>
                                <th>Available</th>
                                <th>Frozen</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                                <tr>
                                    <td>{{ $balance->user->email ?? '#' }}</td>
                                    <td>{{ $balance->asset->symbol ?? '#' }}</td>
                                    <td>{{ $balance->available }}</td>
                                    <td>{{ $balance->frozen }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.balances.edit', $balance) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="{{ route('admin.balances.destroy', $balance) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete balance?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted">No balances found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $balances->links() }}
            </div>
        </div>
    </div>
@endsection
