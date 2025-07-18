@extends('layouts.app')

@section('title', 'Tambah Pemesanan')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <h3>Tambah Pemesanan</h3>
                    <p class="text-subtitle text-muted">Form untuk menambahkan data pemesanan.</p>
                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Form Pemesanan</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pemesanan.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    {{-- USER --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id">Pengguna</label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="">-- Pilih Pengguna --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- LOKASI --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="lokasi_id">Lokasi</label>
                                        <select name="lokasi_id" id="lokasi_id" class="form-control">
                                            <option value="">-- Pilih Lokasi --</option>
                                            @foreach ($lokasis as $lokasi)
                                                <option value="{{ $lokasi->id }}"
                                                    {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                                    {{ $lokasi->nama_usaha }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lokasi_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- JUMLAH --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control"
                                            value="{{ old('jumlah') }}">
                                        @error('jumlah')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- STATUS --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="Diterima" {{ old('status') == 'Diterima' ? 'selected' : '' }}>
                                                Diterima</option>
                                            <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>
                                                Diproses</option>
                                            <option value="Ditunda" {{ old('status') == 'Ditunda' ? 'selected' : '' }}>
                                                Ditunda
                                            </option>
                                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>
                                                Selesai
                                            </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- TOTAL HARGA --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="total_harga">Total Harga</label>
                                        <input type="number" name="total_harga" id="total_harga" class="form-control"
                                            value="{{ old('total_harga') }}" readonly>
                                        <small class="text-muted">Total harga dihitung otomatis: jumlah × 19.000</small>
                                        @error('total_harga')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- CATATAN --}}
                                    <div class="col-12 mb-3">
                                        <label for="catatan">Catatan</label>
                                        <textarea name="catatan" class="form-control">{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('pemesanan.index') }}" class="btn btn-warning">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </form>
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Choices.js
            new Choices('#user_id', {
                searchEnabled: true,
                itemSelectText: '',
                placeholderValue: 'Pilih Pengguna',
                shouldSort: false,
            });

            new Choices('#lokasi_id', {
                searchEnabled: true,
                itemSelectText: '',
                placeholderValue: 'Pilih Lokasi',
                shouldSort: false,
            });

            new Choices('#status', {
                searchEnabled: false,
                itemSelectText: '',
                shouldSort: false,
            });

            // Hitung total harga
            const jumlahInput = document.getElementById('jumlah');
            const totalHargaInput = document.getElementById('total_harga');

            jumlahInput.addEventListener('input', function() {
                const jumlah = parseInt(jumlahInput.value) || 0;
                const total = jumlah * 19000;
                totalHargaInput.value = total;
            });
        });
    </script>
@endpush
