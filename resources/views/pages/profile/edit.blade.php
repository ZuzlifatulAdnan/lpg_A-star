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
                                <form action="{{ route('profile.update', $user->id) }}" method="POST"
                                    enctype="multipart/form-data">
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
@endpush
