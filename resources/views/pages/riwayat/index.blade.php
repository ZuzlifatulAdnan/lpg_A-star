@extends('layouts.app')

@section('title', 'Riwayat Pemesanan')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Riwayat Pemesanan</h3>
            <p class="text-subtitle text-muted">Halaman untuk melihat riwayat pesanan Anda.</p>
            @include('layouts.alert')
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Riwayat Pemesanan</h4>
                </div>

                <div class="card-body">
                    {{-- Filter --}}
                    <form method="GET" action="{{ route('riwayat.index') }}" class="row g-2 mb-4">
                        <div class="col-md-2">
                            <select name="status_pemesanan" class="form-select">
                                <option value="">Semua Status Pemesanan</option>
                                @foreach ($statusOptions as $opt)
                                    <option value="{{ $opt }}"
                                        {{ request('status_pemesanan') == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="status_pembayaran" class="form-select">
                                <option value="">Semua Status Pembayaran</option>
                                <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas
                                </option>
                                <option value="Menunggu Pembayaran"
                                    {{ request('status_pembayaran') == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu
                                    Pembayaran</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                        {{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex">
                            <button class="btn btn-primary w-50 me-1" type="submit">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary w-50">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table" id="table">
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
                                            @php
                                                $statusClasses = [
                                                    'Diterima' => 'primary',
                                                    'Diproses' => 'warning',
                                                    'Ditunda' => 'danger',
                                                    'Selesai' => 'success',
                                                ];
                                                $statusColor = $statusClasses[$pemesanan->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ $pemesanan->status }}</span>
                                        </td>

                                        {{-- Status Pembayaran --}}
                                        <td>
                                            @php
                                                $pembayaranStatus = optional($pemesanan->pembayaran)->status ?? '-';
                                                $pembayaranClasses = [
                                                    'Pembayaran Berhasil' => 'success',
                                                    'Proses Pembayaran' => 'warning',
                                                    'Menunggu Pembayaran' => 'danger',
                                                ];
                                                $pembayaranColor = $pembayaranClasses[$pembayaranStatus] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $pembayaranColor }}">{{ $pembayaranStatus }}</span>
                                        </td>

                                        {{-- Total --}}
                                        <td>Rp{{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}</td>

                                        {{-- Aksi --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center flex-wrap">
                                                <a href="{{ route('riwayat.show', $pemesanan->id) }}"
                                                    class="btn btn-sm btn-info m-1" data-bs-toggle="tooltip" title="Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if ($pemesanan->pembayaran && $pemesanan->pembayaran->status == 'Menunggu Pembayaran')
                                                    <a href="{{ route('pembayaran.edit_user', $pemesanan->id) }}"
                                                        class="btn btn-sm btn-primary m-1" data-bs-toggle="tooltip"
                                                        title="Bayar">
                                                        <i class="bi bi-credit-card"></i>
                                                    </a>
                                                @endif
                                            </div>
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
                    <div class="mt-4">
                        {{ $pemesanans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
        });
    </script>
@endpush
