<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title')</title>

    @include('layouts.styles')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main" class='layout-navbar'>
            <header class='mb-3'>
                @include('layouts.navbar')
            </header>
            <div id="main-content">

                <div class="page-heading">
                    @yield('breadcrumb')

                    @yield('content')
                </div>

                @include('layouts.footer')
            </div>
        </div>
    </div>
    @include('layouts.scripts')
</body>

</html>
