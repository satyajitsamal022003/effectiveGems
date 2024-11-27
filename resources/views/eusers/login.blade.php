@extends('user.layout')
@section('content')
<section class="as_login_wrapper as_padderTop40 as_padderBottom40">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                <form action="{{route('eusers.postsignin')}}" method="post">
                    @csrf
                    <div class="crs_log_wrap">
                        <div class="crs_log__thumb">
                            <img src="assets/images/login-bg.jpg" class="img-fluid" alt="image">
                        </div>
                        <div class="crs_log__caption">
                            <div class="rcs_log_123">
                                <div class="rcs_ico"><i class="fa fa-user"></i></div>
                            </div>

                            <div class="rcs_log_124">
                                <div class="Lpo09">
                                    <h4>Login Your Account</h4>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Email ID</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="User Name">
                                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="*******">
                                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="main-btn w-100 justify-content-center as_btn">Login
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="crs_log__footer d-flex justify-content-between">
                            <div class="fhg_45">
                                <p class="musrt">Don't have account? <a href="{{route('eusers.signup')}}"
                                        class="theme-cl">SignUp</a></p>
                            </div>
                            <div class="fhg_45">
                                <p class="musrt"><a href="#" class="text-danger">Forgot
                                        Password?</a></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection