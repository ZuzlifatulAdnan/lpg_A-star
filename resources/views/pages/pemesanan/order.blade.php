@extends('layouts.app')

@section('title', 'Tambah Pemesanan')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush

@section('main')
    @if (Auth::user()->role == 'Pelanggan')
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
                            <form action="{{ route('pemesanan.storeOrder') }}" method="POST">
                                @csrf
                                <div class="row">
                                    {{-- JUMLAH --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control"
                                            value="{{ old('jumlah') }}" min="1" required>
                                        @error('jumlah')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- TOTAL HARGA --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="total_harga">Total Harga</label>
                                        <input type="text" name="total_harga" id="total_harga" class="form-control"
                                            value="{{ old('total_harga') }}" readonly>
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
            // Hitung total harga otomatis
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
