@extends('layouts.admin', ['title' => 'Admin - Edit User'])

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input class="form-control" name="name" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password (leave blank to keep)</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-select" name="role">
                                    <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
