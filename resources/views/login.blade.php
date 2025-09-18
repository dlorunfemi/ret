@extends('layouts.app', ['title' => 'Login - Retrixnet'])

@section('content')
    @include('partials.navbar')

    <!-- Account Form -->
    <div class="account_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 m-auto">
                    <div class="card border border-0">
                        <div class="card-header bg-transparent border-0">
                            <img src="{{ asset('img/wallet.png') }}" width="60" height="60" alt="login">
                            <h5 class="mt-3 fw-bold">Login</h5>
                            <p><small>Please log in with your account details</small></p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form method="POST" action="{{ route('login.post') }}" class="row g-3">
                                        @csrf
                                        @if ($errors->any())
                                            <!--<div class="alert alert-danger" role="alert">-->
                                            <!--    <ul class="mb-0">-->
                                            <!--        @foreach ($errors->all() as $error)-->
                                            <!--            <li>{{ $error }}</li>-->
                                            <!--        @endforeach-->
                                            <!--    </ul>-->
                                            <!--</div>-->
                                        @endif
                                        <div class="mb-0">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="Enter your email address">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-0">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" placeholder="Enter your password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mt-2">
                                            <span class="text-decoration-none">
                                                <small><a href="{{ route('signup') }}">Register Now</a> | <a href="#"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal">Forgot
                                                        Password</a></small>
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    id="remember" name="remember">
                                                <label class="form-check-label" for="remember">Remember me</label>
                                            </div>
                                            <div>
                                                <a href="{{ route('password.request') }}" class="small">Forgot
                                                    password?</a>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2">
                                            <button type="submit" class="btn btn_blue">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Account Form -->

    <!-- Form modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent border-0">
                    <img src="{{ asset('img/lock.png') }}" width="60" height="60" alt="Forgot Password">
                    <button type="button" class="btn-close border-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0">
                    <h5 class="fw-bold">Reset Password</h5>
                    <p class="text-warning"><small>*Withdrawal service will be prohibited for 24 hours after resetting
                            password</small></p>
                    <form method="POST" action="{{ route('password.email') }}" class="row g-3">
                        @csrf
                        <div class="mb-0">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail"
                                placeholder="Enter your email address">
                        </div>
                        <div class="d-grid p-0">
                            <button type="submit" class="btn btn_blue">Send Reset Link</button>
                        </div>
                    </form>
                </div>
                <div class="d-grid p-3"></div>
            </div>
        </div>
    </div>
    <!-- Form modal -->
@endsection
