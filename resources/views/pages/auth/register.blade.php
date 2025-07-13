<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar - Sistem Promosi Pariwisata</title>



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
                        <h1 class="auth-title">Daftar</h1>
                        <p class="">Mohon isi data berikut dengan benar.</p>

                        <form action="" method="POST">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="text"
                                    class="form-control form-control-xl @error('name') is-invalid

                                @enderror"
                                    placeholder="Nama Lengkap" name="name">
                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="email"
                                    class="form-control form-control-xl @error('email') is-invalid

                                @enderror"
                                    placeholder="Email" name="email">
                                <div class="form-control-icon">
                                    <i class="bi bi-mailbox-flag"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="password"
                                    class="form-control form-control-xl @error('password') is-invalid
                                @enderror"
                                    placeholder="Password" name="password">
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="password"
                                    class="form-control form-control-xl @error('password_confirmation') is-invalid
                                @enderror"
                                    placeholder="Password Confirmation" name="password_confirmation">
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Daftar</button>
                        </form>
                        <div class="text-center mt-5">
                            <p class="text-gray-600">Sudah punya akun ? <a href="{{ route('login') }}"
                                    class="font-bold">Masuk
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
