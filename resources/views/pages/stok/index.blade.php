@extends('layouts.app')

@section('title', 'Stok LPG')

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
                            <h3>Stok LPG</h3>
                            <p class="text-subtitle text-muted">Halaman untuk melihat stok LPG per user</p>
                        </div>
                    </div>

                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Daftar Stok LPG per User</h4>
                            <a href="{{ route('stok.create') }}" class="btn btn-primary">Tambah Stok</a>
                        </div>

                        <div class="card-body">
                            {{-- Tombol Reset Manual --}}
                            <div class="mb-3">
                                <form action="{{ route('stok.resetManual') }}" method="POST"
                                    onsubmit="return confirm('Yakin reset stok LPG ke 30 untuk semua user & lokasi?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-sync-alt"></i> Reset Stok Manual
                                    </button>
                                </form>
                            </div>
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

                            {{-- Table --}}
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama User</th>
                                            <th class="text-center">Jenis Pemilik</th>
                                            <th class="text-center">Total Jumlah</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stokUsers as $stok)
                                            @php
                                                $user = $stok->user;

                                                $lokasiAll = \App\Models\lokasi::where('user_id', $stok->user_id)
                                                    ->pluck('nama_usaha')
                                                    ->unique();

                                                $lokasiList = $lokasiAll->take(3)->toArray();
                                                $lokasiText = implode(', ', $lokasiList);

                                                if ($lokasiAll->count() > 3) {
                                                    $lokasiText .= ' …';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($stokUsers->currentPage() - 1) * $stokUsers->perPage() + $loop->iteration }}
                                                </td>
                                                <td>{{ $user->name ?? '-' }}</td>
                                                <td class="text-center">{{ ucfirst($stok->jenis_pemilik) }}</td>
                                                <td class="text-center">{{ $stok->total_jumlah }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stok.show', $stok->user_id) }}"
                                                        class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"
                                                        title="Lihat Detail">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
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
