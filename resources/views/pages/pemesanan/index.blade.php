@extends('layouts.app')

@section('title', 'Pemesanan')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Pemesanan</h3>
                        <p class="text-subtitle text-muted">Halaman untuk mengelola data pemesanan LPG 3Kg.</p>
                    </div>
                </div>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pemesanan</h5>
                        <a href="{{ route('pemesanan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Pemesanan
                        </a>
                    </div>

                    <div class="card-body">
                        {{-- Filter --}}
                        <form method="GET" action="{{ route('pemesanan.index') }}">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama/no pemesanan..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">-- Status --</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="bulan" class="form-select">
                                        <option value="">-- Bulan --</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('bulan') == $i ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="tahun" class="form-select">
                                        <option value="">-- Tahun --</option>
                                        @php
                                            $currentYear = now()->year;
                                        @endphp
                                        @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                                            <option value="{{ $y }}"
                                                {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-md-2 mt-2 mt-md-0">
                                    <a href="{{ route('pemesanan.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table" id="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pemesanan</th>
                                        <th>Nama User</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pemesanans as $pemesanan)
                                        <tr>
                                            <td>{{ $loop->iteration + $pemesanans->firstItem() - 1 }}</td>
                                            <td>{{ $pemesanan->no_pemesanan }}</td>
                                            <td>{{ $pemesanan->user->name ?? '-' }}</td>
                                            <td>{{ ucfirst($pemesanan->status) }}</td>
                                            <td>{{ $pemesanan->created_at->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center flex-wrap">
                                                    <a href="{{ route('pemesanan.show', $pemesanan) }}"
                                                        class="btn btn-sm btn-info m-1" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('pemesanan.edit', $pemesanan) }}"
                                                        class="btn btn-sm btn-success m-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('pemesanan.destroy', $pemesanan) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger m-1"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data pemesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap mt-3">
                            <span class="text-muted mb-2">
                                Menampilkan {{ $pemesanans->firstItem() }} sampai {{ $pemesanans->lastItem() }} dari
                                {{ $pemesanans->total() }} entri
                            </span>
                            <nav>
                                {{ $pemesanans->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
