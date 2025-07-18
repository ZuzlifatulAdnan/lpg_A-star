@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <h3>Edit Pengguna</h3>
                </div>
                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form action="{{ route('user.update', $user) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>NIK</label>
                                            <input type="text" name="nik"
                                                class="form-control @error('nik') is-invalid @enderror"
                                                value="{{ old('nik', $user->nik) }}" required>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $user->alamat) }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>No HP</label>
                                            <input type="text" name="no_hp"
                                                class="form-control @error('no_hp') is-invalid @enderror"
                                                value="{{ old('no_hp', $user->no_hp) }}">
                                            @error('no_hp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role" class="form-control @error('role') is-invalid @enderror"
                                                required>
                                                <option value="">-- Pilih Role --</option>
                                                @foreach (['Admin', 'Pelanggan', 'Pangkalan', 'Pengecer'] as $role)
                                                    <option value="{{ $role }}"
                                                        {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Verifikasi</label>
                                            <select name="verifikasi"
                                                class="form-control @error('verifikasi') is-invalid @enderror" required>
                                                <option value="">-- Pilih Verifikasi --</option>
                                                @foreach (['Verifikasi', 'Belum Verifikasi'] as $verifikasi)
                                                    <option value="{{ $verifikasi }}"
                                                        {{ old('verifikasi', $user->verifikasi) == $verifikasi ? 'selected' : '' }}>
                                                        {{ $verifikasi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('verifikasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Password (kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                autocomplete="new-password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                autocomplete="new-password">
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Foto</label><br>
                                            <img id="imagePreview"
                                                src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                                width="100" height="100" class="mb-2">
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                onchange="previewImage(event)">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>KTP</label><br>
                                            <img id="ktpPreview"
                                                src="{{ $user->ktp ? asset('img/user/ktp/' . $user->ktp) : asset('assets/compiled/jpg/2.jpg') }}"
                                                width="100" height="100" class="mb-2">
                                            <input type="file" name="ktp"
                                                class="form-control @error('ktp') is-invalid @enderror"
                                                onchange="previewKtp(event)">
                                            @error('ktp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('user.index') }}" class="btn btn-warning">Batal</a>

                                    </form>

                                </div>
                            </div>
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
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = () => document.getElementById('imagePreview').src = reader.result;
            reader.readAsDataURL(event.target.files[0]);
        }

        function previewKtp(event) {
            const reader = new FileReader();
            reader.onload = () => document.getElementById('ktpPreview').src = reader.result;
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endpush
