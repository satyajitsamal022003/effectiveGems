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
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email" value="{{old('email')}}" required>
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
                                <div class="form-group text-center">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#otpModal">
                                        Login with Mobile OTP
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="crs_log__footer d-flex justify-content-center">
                            <div class="fhg_45"><p class="musrt">Already have an account? <a href="login.html" class="theme-cl">Login</a></p></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpModalLabel">Verify Your Mobile Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="otpForm">
                    @csrf
                    <div class="form-group">
                        <label for="mobile">Enter Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number" required style="background: #c6b5b5;color: black;">
                        <span id="mobileError" class="text-danger"></span>
                    </div>
                    <div class="form-group mt-3">
                        <button type="button" class="btn btn-primary" id="sendOtp">Send OTP</button>
                    </div>
                    <div class="form-group mt-3" id="otpField" style="display: none;">
                        <label for="otp">Enter OTP</label>
                        <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP" required style="background: #c6b5b5;color: black;">
                        <span id="otpError" class="text-danger"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="verifyOtp" style="display: none;">Verify OTP</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch the CSRF token from a meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.getElementById('sendOtp').addEventListener('click', function () {
        const mobile = document.getElementById('mobile').value;

        if (!mobile) {
            document.getElementById('mobileError').textContent = 'Mobile number is required.';
            return;
        }

        fetch('/user/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // Add CSRF token here
            },
            body: JSON.stringify({ mobile }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('otpField').style.display = 'block';
                    document.getElementById('verifyOtp').style.display = 'block';
                    document.getElementById('mobileError').textContent = '';
                } else {
                    document.getElementById('mobileError').textContent = data.message;
                }
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('verifyOtp').addEventListener('click', function () {
        const mobile = document.getElementById('mobile').value;
        const otp = document.getElementById('otp').value;

        if (!otp) {
            document.getElementById('otpError').textContent = 'OTP is required.';
            return;
        }

        toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": 1000,
                "extendedTimeOut": 1000,
                "hideDuration": 1000,
                "showDuration": 1000
            };

        fetch('/user/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken, // Add CSRF token here
            },
            body: JSON.stringify({ mobile, otp }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    setTimeout(function() {
                        window.location.href = '/user/dashboard';
                    }, 1000);
                } else {
                    document.getElementById('otpError').textContent = data.message;
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

@endsection
