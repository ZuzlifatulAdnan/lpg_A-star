<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title') | Aplikasi LPG 3Kg</title>

    <!-- Icon & Favicon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <!-- Admin Mazer CSS -->
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    @stack('style')
</head>

<body>
    <div id="main" class="layout-horizontal">
        <!-- Header -->
        <header class="mb-3">
            <nav class="navbar navbar-expand navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard.index') }}">
                        <img src="{{ asset('img/logo/logo-l.png') }}" alt="Pertamina Logo" height="40">
                        <span class="fw-bold text-primary d-none d-md-inline">Aplikasi LPG</span>
                    </a>
                </div>
            </nav>
        </header>

        <!-- Main content -->
        <div class="main-content container mt-3">
            @yield('main')
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3 text-center">
            <div class="text-muted">&copy; {{ date('Y') }} Aplikasi LPG</div>
        </footer>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    @stack('scripts')
</body>

</html>
