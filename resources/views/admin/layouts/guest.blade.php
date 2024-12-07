<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>{{ config('app.name', 'Breezesoft') }}</title>

    <link rel="stylesheet" href="{{asset('build/assets/app.css')}}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.1/dist/cdn.min.js"></script>
    <!-- Custom styles for this Page-->
    @yield('custom_styles')
</head>
<body class="border-top-wide border-primary d-flex flex-column">

    <div class="page page-center" style="background-size: cover; background-position: center; background-attachment: fixed; background-image: url('{{asset('assets/images/bg/'.rand(1,9).'.jpg')}}')">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ config('app.url') }}" class="navbar-brand navbar-brand-autodark">
                    <img src="{{ asset('logo.png') }}" height="48" alt="" />
                </a>
            </div>

            @yield('content')

        </div>
    </div>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('build/assets/app.js')}}"></script>
    @include('admin.layouts.includes._alpine-init')
    <!-- Page level custom scripts -->
    @yield('custom_scripts')

</body>
</html>
