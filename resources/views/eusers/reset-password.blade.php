@extends('user.layout')
@section('content')
<section class="as_login_wrapper as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                <form action="{{ route('user.password.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="crs_log_wrap">
                        <div class="crs_log__thumb">
                            <img src="{{asset('user/assets/images/login-bg.jpg')}}" class="img-fluid" alt="image">
                        </div>
                        <div class="crs_log__caption">
                            <div class="rcs_log_123">
                                <div class="rcs_ico"><i class="fa fa-key"></i></div>
                            </div>

                            <div class="rcs_log_124">
                                <div class="Lpo09">
                                    <h4>Reset Your Password</h4>
                                    <p>Enter a new password for your account.</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email ID</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ request()->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="*******" required>
                                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="*******" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="main-btn w-100 as_btn">Reset Password</button>
                                </div>
                            </div>
                        </div>
                        <div class="crs_log__footer d-flex justify-content-between">
                            <div class="fhg_45">
                                <p><a href="{{ route('eusers.login') }}" class="text-primary">Back to Login</a></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
