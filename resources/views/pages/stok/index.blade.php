@extends('layouts.app')

@section('title', 'Stok LPG')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Stok LPG</h3>
                            <p class="text-subtitle text-muted">Halaman untuk melihat dan mengelola data stok LPG</p>
                        </div>
                    </div>

                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Daftar Stok LPG</h4>
                            <div class="d-flex gap-2">
                                 <form id="manualResetForm" action="{{ route('stok.manual') }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin me-reset semua stok secara manual?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" title="Tambah stok otomatis untuk semua pelanggan hari ini">
                                        <i class="fas fa-sync"></i> Reset Manual
                                    </button>
                                </form>

                                <a href="{{ route('stok.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Stok
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Filter --}}
                            <form method="GET" class="row g-2 mb-4">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama user…"
                                        value="{{ $search }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="jenis_pemilik" class="form-select">
                                        <option value="">-- Semua Jenis Pemilik --</option>
                                        @foreach ($jenisList as $j)
                                            <option value="{{ $j }}" {{ $jenis == $j ? 'selected' : '' }}>
                                                {{ ucfirst($j) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('stok.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-sync-alt"></i> Reset
                                    </a>
                                </div>
                            </form>

                            {{-- Tabel --}}
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th>Jenis Pemilik</th>
                                            <th>Jumlah</th>
                                            <th>Lokasi Usaha</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stokUsers as $stok)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($stokUsers->currentPage() - 1) * $stokUsers->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $stok->user->name ?? '-' }}</td>
                                                <td>{{ ucfirst($stok->jenis_pemilik) }}</td>
                                                <td>{{ number_format($stok->jumlah) }}</td>
                                                <td>{{ $stok->lokasi->nama_usaha ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stok.edit', $stok->id) }}"
                                                        class="btn btn-sm btn-warning m-1" title="Edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('stok.destroy', $stok->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus stok ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="table-warning">
                                                <td colspan="6" class="text-center text-muted">Tidak ada data stok LPG.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer d-flex justify-content-between mt-3">
                                <span>Menampilkan {{ $stokUsers->count() }} dari {{ $stokUsers->total() }} data</span>
                                {{ $stokUsers->links('pagination::bootstrap-5') }}
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

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
