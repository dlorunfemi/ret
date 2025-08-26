@extends('layouts.app', ['title' => 'App - Retrixnet'])

@section('content')
    @include('partials.navbar')

    <div class="account_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 m-auto">
                    <div class="card border border-0">
                        <div class="card-header bg-transparent border-0">
                            <img src="{{ asset('img/wallet.png') }}" width="60" height="60" alt="">
                            <h5 class="mt-3 fw-bold">Create new account</h5>
                            <p><small>Start Exploring The Crypto World</small></p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form method="POST" action="{{ route('register') }}" class="row g-3">
                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger" role="alert">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="mb-0">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email') }}" placeholder="Please enter your email address">
                                        </div>
                                        <div class="mb-0">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" id="password"
                                                name="password" placeholder="Please choose your password">
                                            <div class="form-text">Use at least 8 characters, with letters, numbers, and
                                                symbols.</div>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-0">
                                            <label for="password_confirmation" class="form-label">Re-type Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Please Re-type your password">
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('login') }}" class="text-decoration-none"><small>Already
                                                    registered? Log In NOW</small></a>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn_blue">Signup</button>
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
@endsection
