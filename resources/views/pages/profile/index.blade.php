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
                                        <img src="{{ Auth::user()->image ? asset('img/user/' . Auth::user()->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                            alt="Avatar" id="imagePreview">
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
                    <!-- Kartu Informasi -->
                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Profil</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Nama</span>
                                        <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Email</span>
                                        <span class="fw-bold">{{ Auth::user()->email }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>NIK</span>
                                        <span class="fw-bold">{{ Auth::user()->nik }}</span>
                                    </li>
                                     <li class="list-group-item d-flex justify-content-between">
                                        <span>No Handphone</span>
                                        <span class="fw-bold">{{ Auth::user()->no_hp }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Alamat</span>
                                        <span class="fw-bold text-end"
                                            style="max-width: 60%;">{{ Auth::user()->alamat }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Verifikasi</span>
                                        <span>
                                            @if (Auth::user()->verifikasi=='Verifikasi')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i>
                                                    Verifikasi</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Belum
                                                    Verifikasi</span>
                                            @endif
                                        </span>
                                    </li>
                                </ul>
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
