@extends('layouts.app', ['title' => 'Reset Password - Retrixnet'])

@section('content')
    @include('partials.navbar')
    <div class="account_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 m-auto">
                    <div class="card border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">Reset Password</h5>
                            <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    <div class="form-text">Use at least 8 characters, with letters, numbers, and symbols.
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-0">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn_blue">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
