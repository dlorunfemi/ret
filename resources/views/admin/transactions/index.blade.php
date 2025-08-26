@extends('layouts.admin', ['title' => 'Admin - Transactions'])

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Transactions</h5>
            <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary btn-sm">New Transaction</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Asset</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
                                <tr>
                                    <td>{{ $tx->id }}</td>
                                    <td>{{ $tx->user->email ?? '#' }}</td>
                                    <td>{{ $tx->asset->symbol ?? '#' }}</td>
                                    <td>{{ $tx->type }}</td>
                                    <td>{{ $tx->amount }}</td>
                                    <td>{{ $tx->status }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-secondary"
                                            href="{{ route('admin.transactions.edit', $tx) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.transactions.destroy', $tx) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete transaction?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
