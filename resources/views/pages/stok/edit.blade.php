@extends('layouts.app')

@section('title', 'Edit Stok LPG Pangkalan')

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
