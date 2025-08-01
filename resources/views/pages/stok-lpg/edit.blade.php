@extends('layouts.app')

@section('title', 'Edit Stok LPG Pengecer')

@section('main')
    @if (Auth::user()->role == 'Pengecer')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Edit Stok LPG</h3>
                            <p class="text-subtitle text-muted">Halaman untuk mengedit data stok LPG Pengecer.</p>
                        </div>
                    </div>
                </div>

                <section class="section">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('stok-lpg.update', $stok->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group mb-3">
                                            <label for="stok_lpg">Jumlah</label>
                                            <input type="number" name="stok_lpg" id="stok_lpg"
                                                class="form-control @error('stok_lpg') is-invalid @enderror"
                                                value="{{ old('stok_lpg', $stok->stok_lpg) }}" min="0" required>
                                            @error('stok_lpg')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group d-flex justify-content-between">
                                            <a href="{{ route('stok-lpg.index') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left me-1"></i> Kembali
                                            </a>
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
