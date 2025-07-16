@extends('layouts.app')

@section('title', 'Beranda')

@section('main')
    <div id="main-content">
        <div class="page-content">

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

                @foreach ($cards as $card)
                    <div class="col-6 col-lg-3 col-md-6 mb-4">
                        <a href="{{ route($card['route']) }}" style="text-decoration: none;">
                            <div class="card shadow-sm border-0">
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
            </section>

            {{-- Daftar Lokasi --}}
            <section class="section mt-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h4 class="card-title mb-0">Daftar Lokasi</h4>
                        <small class="text-muted">Klik tombol untuk melakukan pemesanan atau melihat di Google Maps.</small>
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
                                            <div class="text-muted small">
                                                <span class="badge bg-primary">{{ $lokasi->jenis_usaha }}</span>
                                                <span class="badge bg-info">{{ $lokasi->nama_usaha }}</span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fa fa-map-marker-alt"></i>
                                                {{ $lokasi->latitude }}, {{ $lokasi->longitude }}
                                            </div>
                                        </div>
                                        <div class="mt-2 mt-md-0 d-flex gap-2">
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
