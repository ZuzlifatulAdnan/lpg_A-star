@extends('layouts.app')

@section('title', 'Edit Profil')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
@endpush

@section('main')
    @php
        $role = Auth::user()->role;
    @endphp
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Edit Profil</h3>
                        <p class="text-subtitle text-muted">Ubah informasi profil dan lokasi usaha Anda</p>
                    </div>
                </div>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="row">
                    <!-- Left: Foto dan KTP -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                    class="rounded-circle img-thumbnail" style="width: 150px;" id="imagePreview">

                                <h4 class="mt-3">{{ $user->name }}</h4>
                                <a href="{{ route('profile.index') }}" class="btn btn-warning btn-block mt-2">Profile</a>

                                @if ($user->ktp)
                                    <h6 class="text-muted mt-4">Gambar KTP</h6>
                                    <img src="{{ asset('img/user/ktp/' . $user->ktp) }}" class="img-fluid border rounded"
                                        style="max-height: 250px;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" name="nik" class="form-control"
                                            value="{{ old('nik', $user->nik) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>No HP</label>
                                        <input type="text" name="no_hp" class="form-control"
                                            value="{{ old('no_hp', $user->no_hp) }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Foto Profil</label>
                                        <input type="file" name="file" class="form-control" accept="image/*"
                                            onchange="previewImage(event, 'imagePreview')">
                                    </div>

                                    <div class="form-group">
                                        <label>Foto KTP</label>
                                        <input type="file" name="ktp" class="form-control" accept="image/*"
                                            onchange="previewImage(event, 'ktpPreview')">
                                        <img id="ktpPreview" style="display:none; max-height:150px;"
                                            class="img-thumbnail mt-2">
                                    </div>

                                    @if(in_array($role, ['Admin', 'Pengecer']))
                                        <hr>
                                        <h5>Informasi Lokasi Usaha</h5>

                                        <div class="form-group">
                                            <label>Jenis Usaha</label>
                                            <input type="text" name="jenis_usaha" class="form-control"
                                                value="{{ old('jenis_usaha', optional($user->lokasi)->jenis_usaha) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Usaha</label>
                                            <input type="text" name="nama_usaha" class="form-control"
                                                value="{{ old('nama_usaha', optional($user->lokasi)->nama_usaha) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Lokasi</label>
                                            <textarea name="alamat_lokasi" class="form-control" required>{{ old('alamat_lokasi', optional($user->lokasi)->alamat) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Stok LPG</label>
                                            <input type="number" name="stok_lpg" class="form-control"
                                                value="{{ old('stok_lpg', optional($user->lokasi)->stok_lpg) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Koordinat Lokasi</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="latitude" id="latitude" class="form-control"
                                                        value="{{ old('latitude', optional($user->lokasi)->latitude) }}"
                                                        required placeholder="Latitude">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="longitude" id="longitude" class="form-control"
                                                        value="{{ old('longitude', optional($user->lokasi)->longitude) }}"
                                                        required placeholder="Longitude">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mt-2">
                                            <button type="button" class="btn btn-sm btn-info" onclick="detectLocation()">Deteksi Lokasi Saya</button>
                                        </div>

                                        <div class="form-group">
                                            <div id="map" style="height: 300px;"></div>
                                        </div>
                                    @endif

                                    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
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
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Default koordinat jika tidak ada
        var lat = parseFloat(document.getElementById('latitude')?.value) || -6.2;
        var lng = parseFloat(document.getElementById('longitude')?.value) || 106.8;

        // Inisialisasi peta
        var map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        function detectLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng], 15);
                }, function(error) {
                    alert('Gagal mendeteksi lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung browser ini');
            }
        }

        // function previewImage(event, previewId) {
        //     const reader = new FileReader();
        //     const fileInput = event.target;

        //     reader.onload = function () {
        //         const output = document.getElementById(previewId);
        //         output.src = reader.result;
        //         output.style.display = 'block';
        //     };

        //     if (fileInput.files[0]) {
        //         reader.readAsDataURL(fileInput.files[0]);
        //     }
        // }
    </script>
@endpush
