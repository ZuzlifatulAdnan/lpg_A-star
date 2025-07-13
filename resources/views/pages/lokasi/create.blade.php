@extends('layouts.app')

@section('title', 'Tambah Lokasi')

@push('style')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

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
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Tambah Lokasi</h3>
                        <p class="text-subtitle text-muted">Form untuk menambahkan data lokasi usaha ke sistem.</p>
                    </div>
                </div>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Form Lokasi Usaha</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('lokasi.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- USER -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">Pengguna</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">-- Pilih Pengguna --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- JENIS USAHA -->
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_usaha" class="form-label">Jenis Usaha</label>
                                    <select name="jenis_usaha" class="form-select">
                                        <option value="">-- Pilih Jenis Usaha --</option>
                                        <option value="Pangkalan" {{ old('jenis_usaha') == 'Pangkalan' ? 'selected' : '' }}>
                                            Pangkalan</option>
                                        <option value="Pengecer" {{ old('jenis_usaha') == 'Pengecer' ? 'selected' : '' }}>
                                            Pengecer</option>
                                    </select>
                                    @error('jenis_usaha')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- NAMA USAHA -->
                                <div class="col-md-6 mb-3">
                                    <label for="nama_usaha" class="form-label">Nama Usaha</label>
                                    <input type="text" name="nama_usaha" class="form-control"
                                        value="{{ old('nama_usaha') }}">
                                    @error('nama_usaha')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- ALAMAT -->
                                <div class="col-md-6 mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
                                    @error('alamat')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- LAT / LNG -->
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" id="latitude" name="latitude" class="form-control"
                                        value="{{ old('latitude') }}" readonly>
                                    @error('latitude')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" id="longitude" name="longitude" class="form-control"
                                        value="{{ old('longitude') }}" readonly>
                                    @error('longitude')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- MAP -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Pilih Lokasi di Peta</label>
                                    <div id="map"></div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('lokasi.index') }}" class="btn btn-warning">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <!-- Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Choices.js
            const userChoices = new Choices('#user_id', {
                searchEnabled: true,
                itemSelectText: '',
                placeholderValue: 'Pilih Pengguna',
                shouldSort: false,
            });

            // Leaflet Map
            const defaultLat = -5.3971;
            const defaultLng = 105.2668;

            const map = L.map('map').setView([defaultLat, defaultLng], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function(e) {
                const latlng = marker.getLatLng();
                document.getElementById('latitude').value = latlng.lat.toFixed(6);
                document.getElementById('longitude').value = latlng.lng.toFixed(6);
            });

            // Set default lat/lng if not set
            if (!document.getElementById('latitude').value || !document.getElementById('longitude').value) {
                document.getElementById('latitude').value = defaultLat;
                document.getElementById('longitude').value = defaultLng;
            }
        });
    </script>
@endpush
