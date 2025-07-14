@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <h3>Riwayat Pemesanan</h3>
                <p class="text-subtitle text-muted">Halaman untuk melihat riwayat pesanan Anda.</p>
            </div>

            @include('layouts.alert')

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Riwayat</h5>
                    </div>

                    <div class="card-body">
                        {{-- Filter --}}
                        <form method="GET" action="{{ route('riwayat.index') }}" class="row g-2 mb-3">
                            {{-- Status Pemesanan --}}
                            <div class="col-md-2">
                                <select name="status_pemesanan" class="form-select">
                                    <option value="">-- Semua Status Pemesanan --</option>
                                    @foreach ($statusOptions as $opt)
                                        <option value="{{ $opt }}"
                                            {{ request('status_pemesanan') == $opt ? 'selected' : '' }}>{{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Pembayaran --}}
                            <div class="col-md-2">
                                <select name="status_pembayaran" class="form-select">
                                    <option value="">-- Semua Status Pembayaran --</option>
                                    <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>
                                        Lunas</option>
                                    <option value="Menunggu Pembayaran"
                                        {{ request('status_pembayaran') == 'Menunggu Pembayaran' ? 'selected' : '' }}>
                                        Menunggu Pembayaran</option>
                                </select>
                            </div>

                            {{-- Bulan --}}
                            <div class="col-md-2">
                                <select name="bulan" class="form-select">
                                    <option value="">-- Semua Bulan --</option>
                                    @foreach ($months as $num => $name)
                                        <option value="{{ $num }}"
                                            {{ request('bulan') == $num ? 'selected' : '' }}>
                                            {{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-2">
                                <select name="tahun" class="form-select">
                                    <option value="">-- Semua Tahun --</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Urut --}}
                            <div class="col-md-2">
                                <select name="sort" class="form-select">
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru
                                    </option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search"></i>
                                    Filter</button>
                            </div>
                        </form>

                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Order</th>
                                        <th>Status Pemesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Total Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pemesanans as $pemesanan)
                                        <tr>
                                            <td>{{ $loop->iteration + $pemesanans->firstItem() - 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_order)->format('d-m-Y H:i') }}
                                            </td>

                                            {{-- Status Pemesanan --}}
                                            <td>
                                                @switch($pemesanan->status)
                                                    @case('Diterima')
                                                        <span class="badge bg-primary">Diterima</span>
                                                    @break

                                                    @case('Diproses')
                                                        <span class="badge bg-warning">Diproses</span>
                                                    @break

                                                    @case('Ditunda')
                                                        <span class="badge bg-danger">Ditunda</span>
                                                    @break

                                                    @case('Selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @break

                                                    @default
                                                        <span class="badge bg-secondary">{{ $pemesanan->status }}</span>
                                                @endswitch
                                            </td>

                                            {{-- Status Pembayaran --}}
                                            <td>
                                                @if ($pemesanan->pembayaran->status == 'Pembayaran Berhasil')
                                                    <span class="badge bg-success">Pembayaran Berhasil</span>
                                                @elseif($pemesanan->pembayaran->status == 'Proses Pembayaran')
                                                    <span class="badge bg-warning">Proses Pembayaran</span>
                                                @else
                                                    <span class="badge bg-denger">Menunggu Pembayaran</span>
                                                @endif
                                            </td>

                                            {{-- Total --}}
                                            <td>Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>

                                            {{-- Aksi --}}
                                            <td>
                                                <a href="{{ route('riwayat.show', $pemesanan->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>

                                                {{-- Tampilkan Bayar jika status pembayaran adalah Menunggu Pembayaran --}}
                                                @if ($pemesanan->pembayaran && $pemesanan->pembayaran->status == 'Menunggu Pembayaran')
                                                    <a href="{{ route('pembayaran.edit_user', $pemesanan->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-money-bill"></i> Bayar
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $pemesanans->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endsection
