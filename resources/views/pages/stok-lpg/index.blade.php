@extends('layouts.app')

@section('title', 'Stok LPG Pengecer')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Pengecer')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Stok LPG Pengecer</h3>
                            <p class="text-subtitle text-muted">Halaman untuk melihat dan mengelola data stok LPG Pengecer
                            </p>
                        </div>
                    </div>
                    @include('layouts.alert')
                </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Stok LPG Pengecer</h4>
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
                                                <td>{{ $s->stok_lpg ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('stok-lpg.edit', $s->id) }}"
                                                        class="btn btn-sm btn-warning m-1" title="Edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
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
