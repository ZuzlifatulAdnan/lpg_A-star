@extends('layouts.app')

@section('title', 'Toko Terdekat (A*)')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
            border-radius: 8px;
        }
    </style>
@endpush

@section('main')
    <div id="main-content">
        @include('layouts.alert')
        <div class="page-heading">
            <h3>Toko Terdekat (A*)</h3>
            <p class="text-muted">Pilih metode untuk menentukan lokasi Anda.</p>
        </div>

        <section class="section">
            @if (Auth::check())
                @php
                    $role = Auth::user()->role;
                @endphp
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">Deteksi Lokasi Otomatis</div>
                            <div class="card-body">
                                <button id="detect-location" class="btn btn-outline-primary w-100">Deteksi Lokasi
                                    Sekarang</button>
                            </div>
                        </div>
                    </div>
                    @if ($role === 'Admin')
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-secondary text-white">Pilih Lokasi Manual</div>
                                <div class="card-body">
                                    <form method="GET">
                                        <div id="map"></div>
                                        <div class="mt-3">
                                            <label>Latitude</label>
                                            <input type="text" name="lat" id="lat" class="form-control"
                                                required readonly>
                                        </div>
                                        <div class="mt-2">
                                            <label>Longitude</label>
                                            <input type="text" name="lng" id="lng" class="form-control"
                                                required readonly>
                                        </div>
                                        <button type="submit" class="btn btn-outline-secondary w-100 mt-3">Cari
                                            Toko</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Hasil --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    @if ($errorMessage)
                        <div class="alert alert-danger">{{ $errorMessage }}</div>
                    @elseif (isset($path) && $nearest)
                        <div class="alert alert-success">
                            <strong>Rute ditemukan:</strong>
                            <ul>
                                @foreach ($path as $node)
                                    <li>{{ $node }}</li>
                                @endforeach
                            </ul>
                            <p><strong>Total jarak rute terdekat:</strong> {{ number_format($cost, 2) }} km</p>
                        </div>
                    @endif

                    @if (isset($daftarJarak) && request('lat') && request('lng'))
                        <div class="card">
                            <div class="card-header">
                                <strong>Hasil dari {{ count($daftarJarak) }} toko ditemukan:</strong>
                            </div>
                            <div class="card-body">
                                @php $totalJarak = 0; @endphp
                                @foreach ($daftarJarak as $data)
                                    @php
                                        $store = $data['lokasi'];
                                        $jarak = $data['jarak'];
                                        $totalJarak += $jarak;
                                    @endphp
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body d-flex justify-content-between flex-wrap">
                                            <div>
                                                <h6 class="mb-1">{{ $store->nama_usaha }}</h6>
                                                <div class="text-muted small mb-1">
                                                    <i class="bi bi-briefcase"></i> {{ $store->jenis_usaha }}
                                                    {{-- &nbsp;|&nbsp;
                                                    <i class="bi bi-box"></i> {{ $store->keterangan ?? '-' }} --}}
                                                </div>
                                                <div class="text-muted small mb-1">
                                                    <i class="bi bi-geo-alt"></i> {{ $store->latitude }},
                                                    {{ $store->longitude }}
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-droplet-half"></i> Stok LPG:
                                                    <strong>{{ $store->stok_lpg }}</strong>
                                                </div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-rulers"></i> Jarak:
                                                    <strong>{{ number_format($jarak, 2) }} km</strong>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="https://www.google.com/maps?q={{ $store->latitude }},{{ $store->longitude }}"
                                                    target="_blank" class="btn btn-outline-primary">
                                                    <i class="bi bi-map"></i> Lihat di Maps
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="alert alert-info mt-4">
                                    <strong>Total jarak dari semua toko:</strong> {{ number_format($totalJarak, 2) }} km
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.getElementById('detect-location')?.addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const url = new URL(window.location.href);
                        url.searchParams.set('lat', lat);
                        url.searchParams.set('lng', lng);
                        window.location.href = url.toString();
                    },
                    () => {
                        const url = new URL(window.location.href);
                        url.searchParams.set('error', 'GEOLOCATION_FAILED');
                        window.location.href = url.toString();
                    }
                );
            } else {
                const url = new URL(window.location.href);
                url.searchParams.set('error', 'GEOLOCATION_NOT_SUPPORTED');
                window.location.href = url.toString();
            }
        });

        const defaultLatLng = [-5.3761, 105.2522];
        const map = L.map('map').setView(defaultLatLng, 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);
        let marker;
        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    </script>
@endpush
