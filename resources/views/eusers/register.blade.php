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
                                <div class="Lpo09">
                                    <h4>Create Your Account</h4>
                                </div>

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
                            <div class="fhg_45">
                                <p class="musrt">Already have an account? <a href="login.html" class="theme-cl">Login</a></p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-otp shadow-lg rounded-4">
            <div class="modal-header modal-header-otp bg-primary text-white">
                <h5 class="modal-title" id="otpModalLabel">🔐 Verify Your Mobile Number</h5>
                {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button> --}}
            </div>
            <div class="modal-body p-4">
                <form id="otpForm">
                    @csrf
                    <div class="form-group">
                        <label for="mobile" class="fw-bold">📱 Mobile Number</label>
                        <input type="text"
                            class="form-control form-control-otp rounded-pill bg-light text-dark fw-semibold"
                            id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                        <span id="mobileError" class="text-danger small"></span>
                    </div>
                    <div class="form-group mt-3 text-center">
                        <button type="button" class="btn btn-otp btn-primary px-4 py-2 rounded-pill shadow-sm"
                            id="sendOtp">
                            📩 Send OTP
                        </button>
                        <span id="timer" class="text-danger fw-bold" style="display: none;"></span>
                        <button type="button" class="btn btn-otp btn-secondary px-4 py-2 rounded-pill shadow-sm"
                            id="resendOtp" style="display: none;">
                            🔄 Resend OTP
                        </button>
                    </div>

                    <div class="form-group mt-4" id="otpField" style="display: none;">
                        <label for="otp" class="fw-bold">🔑 Enter OTP</label>
                        <input type="text"
                            class="form-control form-control-otp rounded-pill bg-light text-dark fw-semibold"
                            id="otp" name="otp" placeholder="Enter OTP" required>
                        <span id="otpError" class="text-danger small"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                {{-- <button type="button" class="btn btn-otp btn-secondary rounded-pill px-4 py-2"
                    data-bs-dismiss="modal">❌
                    Close</button> --}}
                <button type="button" class="btn btn-otp btn-success rounded-pill px-4 py-2 shadow-sm"
                    id="verifyOtp" style="display: none;">
                    ✅ Verify OTP
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ url('/') }}/user/assets/js/jquery.js"></script>
<script>
    $(document).ready(function() {

        function startTimer(duration) {
            let timer = duration,
                seconds;
            let countdown = setInterval(function() {
                seconds = timer;
                $('#timer').text(`Resend OTP in ${seconds}s`).show();

                if (--timer < 0) {
                    clearInterval(countdown);
                    $('#timer').hide();
                    $('#resendOtp').show(); // Show Resend OTP button after timer ends
                }
            }, 1000);
        }

        $("#sendOtp, #resendOtp").click(function() {
            const mobile = $('#mobile').val();
            if (!mobile) {
                $('#mobileError').text('Mobile number is required.');
                return;
            }

            let buttonId = $(this).attr('id');
            $('#' + buttonId).prop('disabled', true).text('Processing...');

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            fetch('/user/send-otp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        mobile
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    $('#' + buttonId).prop('disabled', false).text(buttonId === "sendOtp" ?
                        'Send OTP' : 'Resend OTP');
                    if (data.success) {
                        $('#otpField').show();
                        $('#verifyOtp').show();
                        $('#mobileError').text('');

                        // Hide both buttons, show timer, start countdown
                        $('#sendOtp, #resendOtp').hide();
                        startTimer(30);
                    } else {
                        $('#mobileError').text(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#' + buttonId).prop('disabled', false).text(buttonId === "sendOtp" ?
                        'Send OTP' : 'Resend OTP');
                });
        });
    });

    $("#verifyOtp").click(function() {
        const mobile = $('#mobile').val();
        const otp = $('#otp').val();
        if (!otp) {
            $('#otpError').text('OTP is required.');
            return;
        }

        $('#verifyOtp').prop('disabled', true).text('Verifying...');

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
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
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    mobile,
                    otp
                }),
            })
            .then(response => response.json())
            .then(data => {
                $('#verifyOtp').prop('disabled', false).text('Verify OTP');
                if (data.success) {
                    toastr.success(data.message);
                    setTimeout(function() {
                        window.location.href = '/user/dashboard';
                    }, 1000);
                } else {
                    $('#otpError').text(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#verifyOtp').prop('disabled', false).text('Verify OTP');
            });
    });
</script>
<style>
    .modal-body .form-control {
        background-color: #ff8484 !important;
        color: #040404 !important;
    }

    .modal-content .modal-content-otp {
        border: none;
    }

    .modal-header .modal-header-otp {
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }

    .form-control .form-control-otp {
        border: 2px solid #ccc;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .btn .btn-otp {
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>

@endsection