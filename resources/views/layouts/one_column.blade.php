<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('master/dist/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('master/dist/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('master/dist/assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('master/logo.svg') }}" type="image/x-icon">
</head>

<body>
    <nav class="navbar navbar-light">
        <div class="container d-flex flex-column align-items-center py-3">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="{{ asset('master/logo.svg') }}" alt="Logo" style="width: 150px; height: auto;">
            </a>
            <h3 class="mt-3 mb-0">@yield('title')</h3>
        </div>
    </nav>

    <div class="container" style="margin-top: 160px;">
        @yield('content')
    </div>

    <script src="{{ asset('master/dist/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('master/dist/assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>
