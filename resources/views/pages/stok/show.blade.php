@extends('layouts.app')

@section('title', 'Detail Stok - ' . $user->name)

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Detail Stok - {{ $user->name }}</h3>
                            <p class="text-subtitle text-muted">Daftar stok & lokasi untuk user ini</p>
                        </div>
                    </div>

                    @include('layouts.alert')
                </div>

                <section class="section">
                    <a href="{{ route('stok.index') }}" class="btn btn-warning mb-3">← Kembali</a>

                    <div class="card">
                        <div class="card-header">
                            <h4>Data Stok untuk: <strong>{{ $user->name }}</strong></h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Pemilik</th>
                                            <th>Jumlah</th>
                                            <th>Lokasi</th>
                                            <th>Catatan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stokList as $stok)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ ucfirst($stok->jenis_pemilik) }}</td>
                                                <td>{{ $stok->jumlah }}</td>
                                                <td>{{ $stok->lokasi->nama_usaha ?? '-' }}</td>
                                                <td>{{ $stok->catatan ?? '-' }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center flex-wrap">
                                                        <a href="{{ route('stok.edit', $stok->id) }}"
                                                            class="btn btn-sm btn-success btn-icon m-1"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('stok.destroy', $stok->id) }}" method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger btn-icon m-1"
                                                                data-bs-toggle="tooltip" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Tidak ada stok untuk user
                                                    ini.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer d-flex justify-content-between mt-3">
                                <span>Menampilkan {{ $stokList->count() }} dari {{ $stokList->total() }} data</span>
                                {{ $stokList->links('pagination::bootstrap-5') }}
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
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
