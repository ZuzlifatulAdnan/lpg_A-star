@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}"> --}}
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Profile</h3>
                        <p class="text-subtitle text-muted">Halaman tempat pengguna dapat mengubah informasi profil</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                    </div>
                </div>
                @include('layouts.alert')
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('assets/compiled/jpg/2.jpg') }}" alt="Avatar" id="imagePreview">
                                    </div>

                                    <h3 class="mt-3">{{ Auth::user()->name }}</h3>
                                    {{-- <p class="text-small">{{ ucfirst(Auth::user()->role) }}</p> --}}
                                    <!-- Tambahkan Button Edit Data di bawah Nama -->
                                    <a href="{{ route('profile.edit', Auth::user()) }}"
                                        class="btn btn-primary mt-2 btn-block">
                                        Edit Profile
                                    </a>
                                    <!-- Updated Button for Change Password -->
                                    <a href="{{ route('profile.show', Auth::user()) }}"
                                    class="btn btn-warning mt-2 btn-block">Ganti Password</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('profile.update', Auth::user()) }}" method="POST">
                                    @method('Patch')
                                    @csrf
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Your Name" value="{{ Auth::user()->name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Your Email" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" name="text" id="nik" class="form-control"
                                             value="{{ Auth::user()->nik }}" disabled>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    {{-- <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> --}}


    {{-- <script src="{{ asset('assets/compiled/js/app.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script> --}}


    <!-- Page Specific JS File -->
@endpush
