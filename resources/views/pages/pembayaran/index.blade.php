@extends('layouts.app')

@section('title', 'Pembayaran')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Pembayaran</h3>
                            <p class="text-subtitle text-muted">Halaman daftar pembayaran.</p>
                        </div>
                    </div>
                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daftar Pembayaran</h5>
                            <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Pembayaran
                            </a>
                        </div>

                        <div class="card-body">
                            {{-- Filter dan Search --}}
                            <form method="GET" action="{{ route('pembayaran.index') }}">
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <select name="status" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- Semua Status --</option>
                                            <option value="Menunggu Pembayaran"
                                                {{ request('status') == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu
                                                Pembayaran
                                            </option>
                                            <option value="Proses Pembayaran"
                                                {{ request('status') == 'Proses Pembayaran' ? 'selected' : '' }}>
                                                Proses Pembayaran</option>
                                            <option value="Pembayaran Berhasil"
                                                {{ request('status') == 'Pembayaran Berhasil' ? 'selected' : '' }}>
                                                Pembayaran Berhasil</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- Bulan --</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}"
                                                    {{ request('bulan') == $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- Tahun --</option>
                                            @php
                                                $yearNow = now()->year;
                                                $startYear = $yearNow - 5;
                                            @endphp
                                            @for ($y = $yearNow; $y >= $startYear; $y--)
                                                <option value="{{ $y }}"
                                                    {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama atau no pembayaran..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search me-1"></i> Cari
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary w-100">
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
                                            <th>No Pembayaran</th>
                                            <th>Nama Pemesan</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Status</th>
                                            <th>Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pembayaran as $data)
                                            <tr>
                                                <td>{{ $loop->iteration + $pembayaran->firstItem() - 1 }}</td>
                                                <td>{{ $data->no_pembayaran }}</td>
                                                <td>{{ $data->pemesanan->user->name ?? '-' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d M Y') }}</td>
                                                <td>{{ ucfirst($data->status) }}</td>
                                                <td>{{ $data->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('pembayaran.show', $data) }}"
                                                            class="btn btn-info btn-sm me-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('pembayaran.edit', $data) }}"
                                                            class="btn btn-success btn-sm me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('pembayaran.destroy', $data) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus pembayaran ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data pembayaran ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap mt-3">
                                <span class="text-muted mb-2 mb-md-0">
                                    Menampilkan {{ $pembayaran->firstItem() }} sampai {{ $pembayaran->lastItem() }} dari
                                    {{ $pembayaran->total() }} entri
                                </span>
                                <nav class="mb-0">
                                    {{ $pembayaran->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                                </nav>
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
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
