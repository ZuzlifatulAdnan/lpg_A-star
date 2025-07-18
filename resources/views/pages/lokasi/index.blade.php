@extends('layouts.app')

@section('title', 'Lokasi')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan' || Auth::user()->role == 'Pengecer')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Lokasi</h3>
                            <p class="text-subtitle text-muted">Halaman untuk mengelola data lokasi usaha pengguna LPG 3Kg.
                            </p>
                        </div>
                    </div>
                    @include('layouts.alert')
                </div>

                <!-- Basic Tables start -->
                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daftar Lokasi</h5>
                            @if (Auth::user()->role == 'Admin')
                                <a href="{{ route('lokasi.create') }}" class="btn btn-primary">Tambah Lokasi</a>
                            @endif
                        </div>

                        <div class="card-body">
                            {{-- Filter dan Search --}}
                            <form method="GET" action="{{ route('lokasi.index') }}">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Cari nama usaha..." value="{{ request('name') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search me-1"></i> Cari
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('lokasi.index') }}" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-sync-alt me-1"></i> Reset
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
                                            <th>Nama Usaha</th>
                                            <th>Jenis Usaha</th>
                                            <th>Alamat</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>User</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($lokasis as $lokasi)
                                            <tr>
                                                <td>{{ $loop->iteration + $lokasis->firstItem() - 1 }}</td>
                                                <td>{{ $lokasi->nama_usaha }}</td>
                                                <td>{{ $lokasi->jenis_usaha }}</td>
                                                <td>{{ $lokasi->alamat }}</td>
                                                <td>{{ $lokasi->latitude }}</td>
                                                <td>{{ $lokasi->longitude }}</td>
                                                <td>{{ $lokasi->user->name ?? '-' }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center flex-wrap">
                                                        <a href="{{ route('lokasi.show', $lokasi) }}"
                                                            class="btn btn-sm btn-info btn-icon m-1"
                                                            data-bs-toggle="tooltip" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('lokasi.edit', $lokasi) }}"
                                                            class="btn btn-sm btn-success btn-icon m-1"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if (Auth::user()->role == 'Admin')
                                                            <form action="{{ route('lokasi.destroy', $lokasi) }}"
                                                                method="POST"
                                                                onsubmit="this.querySelector('button').disabled = true; return confirm('Yakin ingin menghapus data ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger btn-icon m-1"
                                                                    data-bs-toggle="tooltip" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data lokasi ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap mt-3">
                                <span class="text-muted mb-2 mb-md-0">
                                    Menampilkan {{ $lokasis->firstItem() }} sampai {{ $lokasis->lastItem() }} dari
                                    {{ $lokasis->total() }} entri
                                </span>
                                <nav class="mb-0">
                                    {{ $lokasis->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Tables end -->
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @endif
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>

    <!-- Tooltip Init (Bootstrap 5) -->
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
