@extends('user.layout')
@section('content')
<section class="as_login_wrapper as_padderTop40 as_padderBottom40">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12">
                    <form action="{{route('eusers.postsignup')}}" method="POST">
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
                                    <div class="Lpo09"><h4>Create Your Account</h4></div>

                                    <div class="form-group row mb-0">
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="f_name">First Name</label>
                                                <input type="text" class="form-control" id="f_name" name="f_name" placeholder="First name" value="{{old('f_name')}}" required>
                                                @error('f_name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="l_name">Last Name</label>
                                                <input type="text" class="form-control" id="l_name" name="l_name" placeholder="Last name" value="{{old('l_name')}}" required>
                                                @error('l_name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="email">Your Email</label>
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email" value="email" required>
                                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="*******" required>
                                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="main-btn w-100 justify-content-center as_btn">Signup</button>
                                    </div>
                                </div>
                            </div>
                            <div class="crs_log__footer d-flex justify-content-center">
                                <div class="fhg_45"><p class="musrt">Already have account? <a href="login.html" class="theme-cl">Login</a></p></div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection