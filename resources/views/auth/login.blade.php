<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Admin Login</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/')}}/assets/img/favicon.png?=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/all.min.css">

    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/morris/morris.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/style.css?=1">
    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{url('/')}}/assets/plugins/datatables/datatables.min.css">
    <script src="{{url('/')}}/assets/js/jquery-3.2.1.min.js"></script>
</head>

<body>


    <!-- Main Wrapper -->
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="{{url('/')}}/assets/img/logo.png" alt="Logo">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Login</h1>
                            <p class="account-subtitle">Access to our dashboard</p>
                            <!-- Form -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <input type="hidden" name="" value="">
                                <div class="form-group">
                                
                                    <input class="form-control" name="email" type="email" value="{{old('email')}}" placeholder="Email" >
                                    <span style="color:red">@error('email')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" name="password" placeholder="Password">
                                    <span style="color:red">@error('password')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class="block mt-4">
                                    <label for="remember_me" class="flex items-center">
                                        <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                                </div>
                            </form>
                            <!-- /Form -->

                            <div class="text-center forgotpass">@if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->


    <!-- Bootstrap Core JS -->
    <script src="{{url('/')}}/assets/js/popper.min.js"></script>
    <script src="{{url('/')}}/assets/js/bootstrap.min.js"></script>
    <!-- Slimscroll JS -->
    <script src="{{url('/')}}/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Datatables JS -->
    <script src="{{url('/')}}/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{url('/')}}/assets/plugins/datatables/datatables.min.js"></script>
    <script src="{{url('/')}}/assets/plugins/raphael/raphael.min.js"></script>
    <script src="{{url('/')}}/assets/plugins/morris/morris.min.js"></script>
    <script src="{{url('/')}}/assets/js/chart.morris.js"></script>
    <!-- Custom JS -->
    <script src="{{url('/')}}/assets/js/script.js"></script>

</body>

</html>