@extends('layouts.app')

@section('title', 'Beranda')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Dashboard</h3>
            <p class="text-muted">Statistik dan informasi lokasi LPG.</p>
        </div>

        {{-- Statistik --}}
        <section class="row">
            @php
                $cards = [
                    [
                        'label' => 'Total User',
                        'value' => $users,
                        'color' => 'purple',
                        'icon' => 'fa-users',
                        'route' => 'user.index',
                    ],
                    [
                        'label' => 'Total Lokasi',
                        'value' => $lokasis,
                        'color' => 'blue',
                        'icon' => 'fa-location-dot',
                        'route' => 'lokasi.index',
                    ],
                    [
                        'label' => 'Total Pembayaran',
                        'value' => $pembayarans,
                        'color' => 'green',
                        'icon' => 'fa-money-bill-wave',
                        'route' => 'pembayaran.index',
                    ],
                    [
                        'label' => 'Total Pemesanan',
                        'value' => $pemesanans,
                        'color' => 'red',
                        'icon' => 'fa-receipt',
                        'route' => 'pemesanan.index',
                    ],
                ];
            @endphp
            @if (Auth::user()->role === 'Admin')
                @foreach ($cards as $card)
                    <div class="col-6 col-md-3 mb-4">
                        <a href="{{ route($card['route']) }}" class="text-decoration-none">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body text-center">
                                    <div class="stats-icon {{ $card['color'] }} mb-3 mx-auto">
                                        <i class="fa-solid {{ $card['icon'] }}"></i>
                                    </div>
                                    <h6 class="text-muted">{{ $card['label'] }}</h6>
                                    <h3 class="fw-bold">{{ $card['value'] }}</h3>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </section>

        {{-- Pencarian NIK --}}
        <section class="section mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form action="{{ route('beranda.index') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label for="search-nik" class="form-label">Cari Berdasarkan NIK</label>
                            <input type="text" name="nik" id="search-nik" class="form-control"
                                placeholder="Masukkan NIK..." value="{{ request('nik') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('beranda.index') }}" class="btn btn-secondary">
                                <i class="fa fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- Hasil Pencarian --}}
        @if (request()->filled('nik'))
            @if ($stoks->count())
                <div class="row mt-3">
                    @foreach ($stoks as $stok)
                        @php
                            $user = $stok->user;
                            $nama = optional($user)->name ?? '-';
                            $nik = optional($user)->nik ?? '';
                            $nik_sensored =
                                strlen($nik) > 8
                                    ? substr($nik, 0, 4) . str_repeat('*', strlen($nik) - 8) . substr($nik, -4)
                                    : $nik;
                            $jumlah_stok = number_format($stok->jumlah ?? 0);
                            $lokasi = optional($stok->lokasi)->nama_usaha ?? '-';
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
                                        <p class="mb-1">
                                            <i class="bi bi-geo-alt text-danger me-1"></i>
                                            Lokasi: <strong>{{ $lokasi }}</strong>
                                        </p>
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
                <div class="alert alert-warning text-center mt-3">
                    Tidak ditemukan data untuk pencarian <strong>"{{ request('nik') }}"</strong>.
                </div>
            @endif
        @else
            <div class="alert alert-info text-center mt-3">
                Silakan masukkan NIK untuk menampilkan data stok LPG.
            </div>
        @endif

        {{-- Daftar Lokasi --}}
        <section class="section mt-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <div class="row align-items-center g-2">
                        <div class="col-md-6">
                            <h4 class="card-title mb-1">Daftar Lokasi ({{ $data_lokasis->total() }})</h4>
                            <small class="text-muted">Klik tombol untuk pemesanan atau buka di Google Maps.</small>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('beranda.index') }}" method="GET" class="input-group">
                                <input type="text" name="lokasi" class="form-control form-control-sm"
                                    placeholder="Cari lokasi..." value="{{ request('lokasi') }}">
                                @if (request('nik'))
                                    <input type="hidden" name="nik" value="{{ request('nik') }}">
                                @endif
                                <button class="btn btn-sm btn-primary" type="submit">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="{{ route('beranda.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fa fa-rotate-left"></i> Reset
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($data_lokasis->count() > 0)
                        <div class="list-group">
                            @foreach ($data_lokasis as $lokasi)
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                                    <div class="me-auto">
                                        <strong>{{ $lokasi->nama }}</strong>
                                        <div class="text-muted small">{{ $lokasi->alamat }}</div>
                                        <div class="text-muted small mb-1">
                                            <i class="fa fa-briefcase me-1"></i> {{ $lokasi->jenis_usaha }}
                                            <span class="mx-2">|</span>
                                            <i class="fa fa-store me-1"></i> {{ $lokasi->nama_usaha }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fa fa-map-marker-alt"></i> {{ $lokasi->latitude }},
                                            {{ $lokasi->longitude }}
                                        </div>
                                    </div>
                                    <div class="mt-2 mt-md-0 d-flex gap-2 flex-wrap">
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $lokasi->latitude }},{{ $lokasi->longitude }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-map"></i> Lihat di Maps
                                        </a>
                                        <a href="{{ route('pemesanan.order', ['lokasi_id' => $lokasi->id]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-shopping-cart"></i> Order
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $data_lokasis->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            Tidak ada data lokasi.
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('style')
    <style>
        .stats-icon {
            font-size: 1.5rem;
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
        }

        .stats-icon.purple {
            background-color: #6f42c1;
        }

        .stats-icon.blue {
            background-color: #0d6efd;
        }

        .stats-icon.green {
            background-color: #198754;
        }

        .stats-icon.red {
            background-color: #dc3545;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transition: 0.3s;
        }
    </style>
@endpush
