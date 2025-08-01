@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <h3>Tambah Pengguna</h3>
                </div>
                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>NIK</label>
                                            <input type="text" name="nik"
                                                class="form-control @error('nik') is-invalid @enderror"
                                                value="{{ old('nik') }}" required>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>No HP</label>
                                            <input type="text" name="no_hp"
                                                class="form-control @error('no_hp') is-invalid @enderror"
                                                value="{{ old('no_hp') }}">
                                            @error('no_hp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role" id="role"
                                                class="form-control @error('role') is-invalid @enderror" required>
                                                <option value="">-- Pilih Role --</option>
                                                @foreach (['Admin', 'Pelanggan', 'Pengecer'] as $role)
                                                    <option value="{{ $role }}"
                                                        {{ old('role') == $role ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3" id="jenis_pemilik_group" style="display: none;">
                                            <label for="jenis_pemilik">Kategori Pelanggan</label>
                                            <select name="jenis_pemilik" id="jenis_pemilik"
                                                class="form-control @error('jenis_pemilik') is-invalid @enderror">
                                                <option value="">-- Pilih Kategori Pelanggan --</option>
                                                <option value="UMKM"
                                                    {{ old('jenis_pemilik') == 'UMKM' ? 'selected' : '' }}>UMKM</option>
                                                <option value="Rumah Tangga"
                                                    {{ old('jenis_pemilik') == 'Rumah Tangga' ? 'selected' : '' }}>Rumah
                                                    Tangga</option>
                                                <option value="Pengecer"
                                                    {{ old('jenis_pemilik') == 'Pengecer' ? 'selected' : '' }}>Pengecer
                                                </option>
                                            </select>
                                            @error('jenis_pemilik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3" id="jumlah_group" style="display: none;">
                                            <label for="jumlah">Batas Kuota Pembelian</label>
                                            <input type="number" name="jumlah" id="jumlah"
                                                class="form-control @error('jumlah') is-invalid @enderror"
                                                value="{{ old('jumlah') }}" min="1">
                                            @error('jumlah')
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
                                                        {{ old('verifikasi') == $verifikasi ? 'selected' : '' }}>
                                                        {{ $verifikasi }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('verifikasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror">
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Foto</label><br>
                                            <img id="imagePreviewFoto" src="{{ asset('assets/compiled/jpg/2.jpg') }}"
                                                width="100" height="100" class="mb-2">
                                            <input type="file" name="image"
                                                class="form-control @error('image') is-invalid @enderror"
                                                onchange="previewImage(event, 'imagePreviewFoto')">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>KTP</label><br>
                                            <img id="imagePreviewKTP" src="{{ asset('assets/compiled/jpg/2.jpg') }}"
                                                width="100" height="100" class="mb-2">
                                            <input type="file" name="ktp"
                                                class="form-control @error('ktp') is-invalid @enderror"
                                                onchange="previewImage(event, 'imagePreviewKTP')">
                                            @error('ktp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Simpan</button>
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
        function previewImage(event, targetId) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById(targetId).src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const jenisSelect = document.getElementById('jenis_pemilik');
            const jumlahInput = document.getElementById('jumlah');
            const roleSelect = document.getElementById('role');
            const jenisGroup = document.getElementById('jenis_pemilik_group');
            const jumlahGroup = document.getElementById('jumlah_group');

            // Fungsi tampil/sembunyi berdasarkan role
            function toggleJenisJumlah() {
                if (roleSelect.value === 'Pelanggan') {
                    jenisGroup.style.display = 'block';
                    jumlahGroup.style.display = 'block';
                } else {
                    jenisGroup.style.display = 'none';
                    jumlahGroup.style.display = 'none';
                    jenisSelect.value = '';
                    jumlahInput.value = '';
                }
            }

            // Jalankan saat halaman pertama kali load (misalnya jika ada old value)
            toggleJenisJumlah();

            // Event saat role diubah
            roleSelect.addEventListener('change', toggleJenisJumlah);

            // Auto isi jumlah saat jenis_pemilik dipilih
            jenisSelect.addEventListener('change', function() {
                const selected = this.value;

                if (selected === 'UMKM') {
                    jumlahInput.value = 12;
                    jumlahInput.readOnly = true;
                } else if (selected === 'Rumah Tangga') {
                    jumlahInput.value = 6;
                    jumlahInput.readOnly = true;
                } else {
                    jumlahInput.value = '';
                    jumlahInput.readOnly = false;
                }
            });
        });
    </script>
@endpush
