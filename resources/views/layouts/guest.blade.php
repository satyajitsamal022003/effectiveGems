<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/all.min.css">
    <link rel="stylesheet" href="{{url('/')}}/assets/css/style.css">

    <!-- jQuery -->
    <script src="{{url('/')}}/assets/js/jquery-3.2.1.min.js"></script>
</head>
<body class="font-sans text-gray-900 antialiased">

    @yield('content')

    <!-- Bootstrap Core JS -->
    <script src="{{url('/')}}/assets/js/popper.min.js"></script>
    <script src="{{url('/')}}/assets/js/bootstrap.min.js"></script>
    <script src="{{url('/')}}/assets/js/script.js"></script>

</body>
</html>
