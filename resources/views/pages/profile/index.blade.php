@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
                </div>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="avatar avatar-2xl mb-3">
                                    <img src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                        alt="Avatar" id="imagePreview" class="rounded-circle img-fluid">
                                </div>
                                <h4>{{ $user->name }}</h4>

                                <a href="{{ route('profile.edit', $user) }}" class="btn btn-primary btn-block mt-3">
                                    <i class="bi bi-pencil-square"></i> Edit Profile
                                </a>
                                <a href="{{ route('profile.show', $user) }}" class="btn btn-warning btn-block mt-2">
                                    <i class="bi bi-key-fill"></i> Ganti Password
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Profil</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Nama</span>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Email</span>
                                        <span class="fw-bold">{{ $user->email }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>NIK</span>
                                        <span class="fw-bold">{{ $user->nik }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>No Handphone</span>
                                        <span class="fw-bold">{{ $user->no_hp }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Alamat</span>
                                        <span class="fw-bold text-end" style="max-width: 60%;">{{ $user->alamat }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Verifikasi</span>
                                        <span>
                                            @if ($user->verifikasi === 'Verifikasi')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i>
                                                    Verifikasi</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Belum
                                                    Verifikasi</span>
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <span class="fw-bold d-block mb-2">Foto KTP</span>
                                        @if ($user->ktp)
                                            <div class="text-center">
                                                <img src="{{ asset('img/user/ktp/' . $user->ktp) }}"
                                                    alt="Foto KTP {{ $user->name }}" class="img-fluid rounded shadow-sm"
                                                    style="max-height: 250px;">
                                            </div>
                                        @else
                                            <p class="text-muted">Belum mengunggah KTP.</p>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @php
                            $lokasi = $user->lokasi ?? null;
                        @endphp

                        @if ($lokasi)
                            <div class="card shadow-sm mt-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informasi Lokasi Usaha</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Jenis Usaha</span>
                                            <span class="fw-bold">{{ $lokasi->jenis_usaha }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Nama Usaha</span>
                                            <span class="fw-bold">{{ $lokasi->nama_usaha }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Alamat Lokasi</span>
                                            <span class="fw-bold text-end"
                                                style="max-width: 60%;">{{ $lokasi->alamat }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Stok LPG</span>
                                            <span class="fw-bold">{{ $lokasi->stok_lpg }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Latitude</span>
                                            <span class="fw-bold">{{ $lokasi->latitude }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Longitude</span>
                                            <span class="fw-bold">{{ $lokasi->longitude }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card shadow-sm mt-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Peta Lokasi Usaha</h5>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="height: 300px;" class="rounded border"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if ($lokasi)
                const lat = {{ $lokasi->latitude }};
                const lng = {{ $lokasi->longitude }};

                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup("Lokasi Usaha: {{ $lokasi->nama_usaha }}")
                    .openPopup();
            @endif
        });
    </script>
@endpush
