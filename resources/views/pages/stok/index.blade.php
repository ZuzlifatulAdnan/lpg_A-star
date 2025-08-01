@extends('layouts.app')

@section('title', 'Stok LPG Pangkalan')

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
                            <h4>Stok LPG Pangkalan</h4>
                            @if ($stok->count() == 0)
                                <a href="{{ route('stok.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Stok
                                </a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jumlah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stok as $s)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($stok->currentPage() - 1) * $stok->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $s->jumlah ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stok.edit', $s->id) }}"
                                                        class="btn btn-sm btn-warning m-1" title="Edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('stok.destroy', $s->id) }}" method="POST"
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
                                                <td colspan="3" class="text-center text-muted">Tidak ada data stok LPG.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer d-flex justify-content-between mt-3">
                                <span>Menampilkan {{ $stok->count() }} dari {{ $stok->total() }} data</span>
                                {{ $stok->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @elseif(Auth::user()->role == 'Pengecer')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Stok LPG Pengecer</h3>
                            <p class="text-subtitle text-muted">Halaman untuk melihat dan mengelola data stok LPG Pengecer</p>
                        </div>
                    </div>
                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Stok LPG Pengecer</h4>
                            @if ($lokasi->count() == 0)
                                <a href="{{ route('stok.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Stok
                                </a>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jumlah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($lokasi as $s)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($lokasi->currentPage() - 1) * $stok->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $s->jumlah ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stok-lpg.edit', $s->id) }}"
                                                        class="btn btn-sm btn-warning m-1" title="Edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('stok-lpg.destroy', $s->id) }}" method="POST"
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
                                                <td colspan="3" class="text-center text-muted">Tidak ada data stok LPG.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer d-flex justify-content-between mt-3">
                                <span>Menampilkan {{ $lokasi->count() }} dari {{ $lokasi->total() }} data</span>
                                {{ $lokasi->links('pagination::bootstrap-5') }}
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
