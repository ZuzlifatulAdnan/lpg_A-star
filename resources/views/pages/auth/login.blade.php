<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Masuk - Sistem Promosi Pariwisata</title>



        <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/compiled/css/auth.css') }}">
    </head>

    <body>
        <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
        <div id="auth">

            <div class="row h-100">
                <div class="col-lg-5 col-12">
                    <div id="auth-left">
                        <div class="auth-logo">
                        </div>
                        <h1 class="auth-title">Masuk</h1>
                        @if (session('status'))
                            <p>{{ session('status') }}, Silahkan login kembali.</p>.
                        @else
                            <p class="">Gunakan Email & Password anda untuk masuk.</p>
                        @endif


                        <form action="" method="POST">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="email"
                                    class="form-control form-control-xl @error('email') is-invalid

                                @enderror"
                                    placeholder="Email" name="email">
                                <div class="form-control-icon">
                                    <i class="bi bi-mailbox-flag"></i>
                                </div>
                            </div>
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="password"
                                    class="form-control form-control-xl @error('email') is-invalid
                                @enderror"
                                    placeholder="Password" name="password">
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Masuk</button>
                        </form>
                        <div class="text-center mt-5">
                            <p><a class="font-bold" href="{{ route('password.request') }}">Lupa password ?</a></p>
                            <p class="text-gray-600">Belum punya akun ? <a href="{{ route('register') }}"
                                    class="font-bold">Daftar
                                </a></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 d-none d-lg-block">
                    <div id="auth-right" class="h-100">
                        <img src="{{ asset('img/logo/auth.jpg') }}" 
                             alt="Sistem Promosi Pariwisata" 
                             class="img-fluid h-100 w-100 object-cover">
                    </div>
                </div>
            </div>

        </div>
    </body>

</html>
