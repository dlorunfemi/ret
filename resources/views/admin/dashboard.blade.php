@extends('layouts.admin', ['title' => 'Admin Dashboard - Retrixnet'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Admin Dashboard</h5>
                    </div>
                    <div class="card-body">


                        <div class="row g-3">
                            {{-- <div class="col-lg-6">
                                <h6 class="fw-bold">Users</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($users ?? collect()) as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('admin.users.updateRole', $user) }}"
                                                            class="d-flex align-items-center gap-2">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="role" class="form-select form-select-sm"
                                                                style="width:auto">
                                                                <option value="user" @selected($user->role === 'user')>User
                                                                </option>
                                                                <option value="admin" @selected($user->role === 'admin')>Admin
                                                                </option>
                                                            </select>
                                                            <button class="btn btn-sm btn-primary">Save</button>
                                                        </form>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-muted">No users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (isset($users))
                                    {{ $users->links() }}
                                @endif
                            </div> --}}
                            <div class="col-lg-6">
                                <h6 class="fw-bold">Pending Withdrawals</h6>
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Asset</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($pendingWithdrawals ?? collect()) as $tx)
                                                <tr>
                                                    <td>{{ $tx->user->email ?? '#' }}</td>
                                                    <td>{{ $tx->asset->symbol ?? '#' }}</td>
                                                    <td>{{ $tx->amount }}</td>
                                                    <td><span class="badge text-bg-warning">{{ $tx->status }}</span></td>
                                                    <td>
                                                        <form method="POST"
                                                            action="{{ route('admin.withdrawals.approve', $tx) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="btn btn-sm btn-success">Approve</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-muted">No pending withdrawals.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (isset($pendingWithdrawals))
                                    {{ $pendingWithdrawals->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
