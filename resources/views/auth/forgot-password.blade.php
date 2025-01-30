@extends('layouts.guest')

@section('content')
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <div class="loginbox">
                <div class="login-left">
                    <img class="img-fluid" src="{{ url('/') }}/assets/img/logo.png" alt="Logo">
                </div>
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Forgot Password</h1>
                        <p class="account-subtitle">
                            {{ __('Enter your email address below and we will send you a password reset link.') }}
                        </p>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group">
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                <span style="color:red">@error('email') {{ $message }} @enderror</span>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                        <!-- /Form -->

                        <div class="text-center forgotpass">
                            <a href="{{ route('login') }}">{{ __('Back to Login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
