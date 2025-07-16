@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Edit Profile</h3>
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
                                    <a href="{{ route('profile.index') }}" class="btn btn-warning mt-2 btn-block">
                                        Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('profile.update', Auth::user()->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="name">Nama</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', Auth::user()->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="nik">NIK</label>
                                        <input type="number" name="nik" id="nik"
                                            class="form-control @error('nik') is-invalid @enderror"
                                            value="{{ old('nik', Auth::user()->nik) }}" required>
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>No HP</label>
                                        <input type="text" name="no_hp"
                                            class="form-control @error('no_hp') is-invalid @enderror"
                                            value="{{ old('no_hp', Auth::user()->no_hp) }}">
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="file">Gambar</label><br>
                                        <input type="file" name="file" id="file"
                                            class="form-control @error('file') is-invalid @enderror" accept="image/*"
                                            onchange="previewImage(event)">
                                        @error('file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
