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
                        <h1>Reset Password</h1>
                        <p class="account-subtitle">Enter your new password to reset it.</p>

                        <!-- Form -->
                        <form method="POST" action="{{ route('password.store') }}">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" value="{{ old('email', request()->email) }}" required autofocus>
                                <span style="color:red">@error('email') {{ $message }} @enderror</span>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password" required>
                                <span style="color:red">@error('password') {{ $message }} @enderror</span>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation" required>
                                <span style="color:red">@error('password_confirmation') {{ $message }} @enderror</span>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                            </div>
                        </form>
                        <!-- /Form -->

                        <div class="text-center">
                            <a href="{{ route('login') }}">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
