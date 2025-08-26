@extends('layouts.app', ['title' => 'Forgot Password - Retrixnet'])

@section('content')
    @include('partials.navbar')
    <div class="account_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 m-auto">
                    <div class="card border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">Forgot Password</h5>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                            @endif
                            <form method="POST" action="{{ route('password.email') }}" class="row g-3">
                                @csrf
                                <div class="mb-0">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter your email address"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn_blue">Send Reset Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
