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
    <link rel="icon" href="{{ asset('favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- General CSS -->
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    @stack('style')
</head>

<body>
    <div id="main" class="layout-horizontal">
        <!-- Header -->
        <header class="mb-3">
            <nav class="navbar navbar-expand navbar-light">
                <div class="container-fluid">
                    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard.index') }}">
                        <img src="{{ asset('img/logo/logo.png') }}" alt="Logo LPG" class="img-fluid"
                            style="height: 60px;">

                        <span class="fw-bold text-primary d-none d-md-inline">Aplikasi LPG</span>
                    </a>

                    @if (Auth::check())
                        <div class="d-flex align-items-center ms-auto gap-2">
                            <!-- Tombol Beranda -->
                            @if (Auth::user()->role == 'Admin')
                                <a href="{{ route('beranda.index') }}" class="btn btn-outline-primary"
                                    aria-label="Beranda">
                                    <i class="bi bi-house-door-fill me-1"></i> Beranda
                                </a>
                            @elseif(Auth::user()->role == 'Pelanggan')
                                <a href="{{ route('pemesanan.Order') }}" class="btn btn-outline-primary"
                                    aria-label="Pemesanan">
                                    <i class="bi bi-house-door-fill me-1"></i> Pemesanan
                                </a>
                            @else
                                <a href="{{ route('stok-lpg.index') }}" class="btn btn-outline-primary"
                                    aria-label="Stok LPG">
                                    <i class="bi bi-house-door-fill me-1"></i> Stok LPG
                                </a>
                            @endif


                            <!-- Tombol Logout -->
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" aria-label="Logout">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Dark Mode Toggle -->
                    <div class="theme-toggle d-flex gap-2 align-items-center ms-3 mt-3" title="Toggle Dark Mode">
                        <svg xmlns="http://www.w3.org/2000/svg" class="iconify iconify--system-uicons" width="20"
                            height="20" viewBox="0 0 21 21">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                    opacity=".3"></path>
                                <g transform="translate(-210 -1)">
                                    <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                    <circle cx="220.5" cy="11.5" r="4"></circle>
                                    <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                    </path>
                                </g>
                            </g>
                        </svg>
                        <div class="form-check form-switch fs-6">
                            <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                                style="cursor: pointer">
                            <label class="form-check-label" for="toggle-dark"></label>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="iconify iconify--mdi" width="20"
                            height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="main-content container mt-3">
            @yield('main')
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3 text-center">
            <div class="text-muted">&copy; {{ date('Y') }} Aplikasi LPG</div>
        </footer>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    @stack('scripts')
</body>

</html>
