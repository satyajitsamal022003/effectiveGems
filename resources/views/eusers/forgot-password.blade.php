@extends('user.layout')
@section('content')
<section class="as_login_wrapper as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                <form action="{{ route('user.password.email') }}" method="post">
                    @csrf
                    <div class="crs_log_wrap">
                        <div class="crs_log__thumb">
                            <img src="assets/images/login-bg.jpg" class="img-fluid" alt="image">
                        </div>
                        <div class="crs_log__caption">
                            <div class="rcs_log_123">
                                <div class="rcs_ico"><i class="fa fa-lock"></i></div>
                            </div>

                            <div class="rcs_log_124">
                                <div class="Lpo09">
                                    <h4>Forgot Password</h4>
                                    <p>Enter your email, and we'll send you a reset link.</p>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email ID</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="main-btn w-100 as_btn">Send Reset Link</button>
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
