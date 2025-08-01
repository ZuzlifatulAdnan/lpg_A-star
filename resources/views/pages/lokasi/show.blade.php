@extends('layouts.app')

@section('title', 'Detail Data Pengecer')

@push('style')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        #map {
            width: 100%;
            height: 350px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
        }
    </style>
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row mb-2">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Data Pengecer</h3>
                            <p class="text-subtitle text-muted">Halaman ini menampilkan informasi lengkap Data Pengecerusaha.</p>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Informasi Data Pengeceri</h5>
                            <a href="{{ route('lokasi.index') }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Pengguna</label>
                                    <div>{{ $lokasi->user->name ?? '-' }} ({{ $lokasi->user->email ?? '-' }})</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Jenis Usaha</label>
                                    <div>{{ $lokasi->jenis_usaha }}</div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Nama Usaha</label>
                                    <div>{{ $lokasi->nama_usaha }}</div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Alamat</label>
                                    <div>{{ $lokasi->alamat }}</div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Stok Lpg</label>
                                    <div>{{ $lokasi->stok_lpg }}</div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Latitude</label>
                                    <div>{{ $lokasi->latitude }}</div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label class="fw-bold">Longitude</label>
                                    <div>{{ $lokasi->longitude }}</div>
                                </div>
                            </div>

                            <hr>
                            <h6 class="fw-bold mb-2">Lokasi Usaha di Peta</h6>
                            <div id="map"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @endif
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lat = {{ $lokasi->latitude ?? -5.3971 }};
            const lng = {{ $lokasi->longitude ?? 105.2668 }};

            const map = L.map('map').setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup("{{ $lokasi->nama_usaha }}<br>{{ $lokasi->alamat }}")
                .openPopup();
        });
    </script>
@endpush
