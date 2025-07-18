@extends('layouts.app')

@section('title', 'Edit Stok LPG')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Edit Stok LPG</h3>
                            <p class="text-subtitle text-muted">Halaman untuk mengedit data stok LPG.</p>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('stok.update', $stok->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        {{-- USER --}}
                                        <div class="form-group mb-3">
                                            <label for="user_id">User</label>
                                            <select name="user_id" id="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih User --</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id', $stok->user_id) == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- JENIS PEMILIK --}}
                                        <div class="form-group mb-3">
                                            <label for="jenis_pemilik">Jenis Pemilik</label>
                                            <select name="jenis_pemilik" id="jenis_pemilik"
                                                class="form-control @error('jenis_pemilik') is-invalid @enderror" required>
                                                <option value="">-- Pilih Jenis Pemilik --</option>
                                                @foreach (['Pengecer', 'UMKM', 'Rumah Tangga'] as $jenis)
                                                    <option value="{{ $jenis }}"
                                                        {{ old('jenis_pemilik', $stok->jenis_pemilik) == $jenis ? 'selected' : '' }}>
                                                        {{ $jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jenis_pemilik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- JUMLAH --}}
                                        <div class="form-group mb-3">
                                            <label for="jumlah">Jumlah</label>
                                            <input type="number" name="jumlah" id="jumlah"
                                                class="form-control @error('jumlah') is-invalid @enderror"
                                                value="{{ old('jumlah', $stok->jumlah) }}" min="1" required>
                                            @error('jumlah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- LOKASI --}}
                                        <div class="form-group mb-3">
                                            <label for="lokasi_id">Lokasi</label>
                                            <select name="lokasi_id" id="lokasi_id"
                                                class="form-control @error('lokasi_id') is-invalid @enderror" required>
                                                @foreach ($lokasi as $lok)
                                                    <option value="{{ $lok->id }}"
                                                        {{ in_array($lok->id, old('lokasi_id', $stok->lokasi->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                        {{ $lok->nama_usaha }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Bisa pilih lebih dari satu lokasi</small>
                                            @error('lokasi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- CATATAN --}}
                                        <div class="form-group mb-3">
                                            <label for="catatan">Catatan</label>
                                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $stok->catatan) }}</textarea>
                                            @error('catatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-pencil-square me-1"></i> Perbarui
                                            </button>
                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userSelect = document.getElementById('user_id');
            const jenisSelect = document.getElementById('jenis_pemilik');
            const lokasiSelect = document.getElementById('lokasi_id');
            const jumlahInput = document.getElementById('jumlah');

            new Choices(userSelect, {
                searchEnabled: true,
                itemSelectText: '',
            });

            new Choices(jenisSelect, {
                searchEnabled: false,
                itemSelectText: '',
            });

            new Choices(lokasiSelect, {
                removeItemButton: true,
                searchEnabled: true,
            });

            // Auto isi jumlah jika dipilih jenis tertentu
            jenisSelect.addEventListener('change', function() {
                const selected = this.value;

                if (selected === 'UMKM') {
                    jumlahInput.value = 5;
                } else if (selected === 'Rumah Tangga') {
                    jumlahInput.value = 3;
                }
            });
        });
    </script>
@endpush
