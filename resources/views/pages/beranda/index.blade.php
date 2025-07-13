@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
@endpush

@section('main')

    @if (Auth::user())
        <div id="main-content">
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="row">
                            <!-- Total User -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <a href="{{ route('user.index') }}">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon purple mb-2">
                                                        <i class="fa-solid fa-users"></i> <!-- Ikon untuk User -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Total User</h6>
                                                    <h6 class="font-extrabold mb-0">{{ $users->count() }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Total Berita -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <a href="">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="fa-solid fa-newspaper"></i> <!-- Ikon Berita -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Total Berita</h6>
                                                    {{-- <h6 class="font-extrabold mb-0">{{ $beritas->count() }}</h6> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Total Foto di Galeri -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <a href="">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon green mb-2">
                                                        <i class="fa-solid fa-images"></i> <!-- Ikon Foto -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Total Foto di Galeri</h6>
                                                    {{-- <h6 class="font-extrabold mb-0">{{ $fotos->count() }}</h6> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Total Video di Galeri -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <a href="">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon red mb-2">
                                                        <i class="fa-solid fa-video"></i> <!-- Ikon Video -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Total Video di Galeri</h6>
                                                    {{-- <h6 class="font-extrabold mb-0">{{ $vidios->count() }}</h6> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Total Review -->
                            <div class="col-6 col-lg-3 col-md-6">
                                <a href="">
                                    <div class="card">
                                        <div class="card-body px-4 py-4-5">
                                            <div class="row">
                                                <div
                                                    class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                    <div class="stats-icon blue mb-2">
                                                        <i class="fa-solid fa-comment"></i> <!-- Ikon Review -->
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                    <h6 class="text-muted font-semibold">Total Review</h6>
                                                    {{-- <h6 class="font-extrabold mb-0">{{ $reviews->count() }}</h6> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
    <div id="main-content">
        <div class="page-heading">
            <div class="card-body p-0">
                <!-- Banner dengan ukuran responsif -->
                <img src="{{ asset('img/logo/bannner.png') }}" class="img-fluid w-100" style="object-fit: cover; max-height: 380px;" alt="Hero Banner">
            </div>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    <!-- Informasi -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="mb-2">Informasi Cuku Nyinyi</h5>
                        </div>
                    </div>
    
                    <!-- Baris pertama: Jam Operasional dan Harga Tiket -->
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5>Jam Operasional</h5>
                                    <p class="h6 text-muted">06:30 s/d 18:00 WIB</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5>Harga Tiket</h5>
                                    <p class="h6 text-muted">Rp 15.000</p>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Baris kedua: Alamat dan Spot Foto -->
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5>Alamat</h5>
                                    {{-- <p class="h6 text-muted">Desa Sidodadi, Pesawaran</p> --}}
                                    <!-- Embed Google Maps -->
                                    <iframe 
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.0313381626165!2d105.24214797406968!3d-5.5623734552487365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e4127c5df70b9b9%3A0xf72a7855e870c45!2sMangrove%20cuku%20NyiNyi!5e0!3m2!1sid!2sid!4v1734273552399!5m2!1sid!2sid" 
                                        width="100%" 
                                        height="300" 
                                        style="border:0;" 
                                        allowfullscreen="" 
                                        loading="lazy" 
                                        referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                    <a href="https://www.google.com/maps/place/Mangrove+cuku+NyiNyi/@-5.5623735,105.242148" target="_blank" class="btn btn-outline-primary btn-sm mt-2" rel="noopener noreferrer">Lihat di Google Maps</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h5>Spot Foto</h5>
                                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('img/logo/spotfoto.jpg') }}" class="d-block w-100" alt="Foto 1">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('img/logo/spotfoto2.jpg') }}" class="d-block w-100" alt="Foto 2">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('img/logo/spotfoto3.jpg') }}" class="d-block w-100" alt="Foto 3">
                                            </div>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Baris ketiga: Fasilitas -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5>Fasilitas</h5>
                            <ul class="list-unstyled mt-2">
                                <li><i class="bi bi-check-circle-fill text-success"></i> Musholla</li>
                                <li><i class="bi bi-check-circle-fill text-success"></i> Kamar Mandi</li>
                                <li><i class="bi bi-check-circle-fill text-success"></i> Aula</li>
                            </ul>
                        </div>
                    </div>
    
                    <!-- Kontak Person -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5>Kontak Person</h5>
                            <p class="h6 text-muted">Untuk informasi lebih lanjut, hubungi kami di 08123456789.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    
    @endif


@endsection

@push('scripts')
    <!-- JS Libraies -->
    {{-- <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script> --}}


    <!-- Page Specific JS File -->
@endpush