@extends('layouts.app', ['title' => 'Verify Email - Retrixnet'])

@section('content')
    @include('partials.navbar')
    <div class="account_form">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card border-0">
                        <div class="card-body mx-auto">
                            <h5 class="fw-bold">Verify your email</h5>
                            <p class="text-muted">We have sent a verification link to your email address.</p>
                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success" role="alert">
                                    A new verification link has been sent to your email address.
                                </div>
                            @endif
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn_blue">Resend Verification Email</button>
                                </form>
                                <!--<form method="POST" action="{{ route('logout') }}">-->
                                <!--    @csrf-->
                                <!--    <button type="submit" class="btn btn_blue_outline">Logout</button>-->
                                <!--</form>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
