@extends('layouts.dashboard')

@section('title', 'Dashboard Stok LPG')

@section('main')
    <div class="page-heading mt-4">
        <h3>Dashboard Stok LPG</h3>
    </div>

    <section class="section mt-3">
        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('dashboard.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="name" class="form-control form-control-lg" value="{{ request('name') }}"
                        placeholder="Cari berdasarkan nama atau NIK...">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-lg btn-primary" type="submit">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </form>

        <!-- Data hanya tampil saat pencarian -->
        @if (request()->filled('name'))
            @if ($stoks->count())
                <div class="row">
                    @foreach ($stoks as $stok)
                        @php
                            $nama = $stok->name ?? '-';
                            $nik = $user->nik ?? '';
                            $nik_sensored =
                                strlen($nik) > 8
                                    ? substr($nik, 0, 4) . str_repeat('*', strlen($nik) - 8) . substr($nik, -4)
                                    : $nik;
                            $jumlah_stok = number_format($stok->jumlah ?? 0);
                        @endphp

                        @if (($stok->jumlah ?? 0) == 0)
                            {{-- Kartu blok jika stok kosong --}}
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div
                                    class="card h-100 shadow-sm border-0 bg-light text-center d-flex flex-column justify-content-center">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="fa fa-box-open fa-2x text-danger"></i>
                                        </div>
                                        <h6 class="text-danger mb-2">Stok LPG Habis</h6>
                                        <p class="text-muted small">Silakan cek toko lain yang memiliki stok tersedia.</p>
                                        <a href="{{ route('toko.index') }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-store"></i> Cek Toko Lain
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Kartu stok normal --}}
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar bg-primary text-white me-3">
                                                <i class="fa fa-user fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $nama }}</h6>
                                                <small class="text-muted">NIK: {{ $nik_sensored }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-0">
                                            <i class="bi bi-box-seam text-success me-1"></i>
                                            Stok LPG: <strong>{{ $jumlah_stok }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $stoks->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-warning text-center">
                    Tidak ditemukan data untuk pencarian <strong>"{{ request('name') }}"</strong>.
                </div>
            @endif
        @else
            <div class="alert alert-info text-center">
                Silakan masukkan nama atau NIK untuk menampilkan data stok LPG.
            </div>
        @endif
    </section>
@endsection
