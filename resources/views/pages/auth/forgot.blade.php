<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lupa Password - Sistem Promosi Pariwisata</title>



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
                        <h1 class="auth-title">Lupa Password</h1>
                        <p class="">Mohon isi dengan email yang terdaftar.</p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-4">
                                <input type="text"
                                    class="form-control form-control-xl @error('email') is-invalid

                                @enderror"
                                    placeholder="Email" name="email">
                                <div class="form-control-icon">
                                    <i class="bi  bi-mailbox-flag"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button class="btn btn-primary btn-block btn-lg shadow-lg mt-3">Kirim Link Reset
                                Password</button>
                        </form>
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
