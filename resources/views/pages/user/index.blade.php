@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <h3>Daftar Pengguna</h3>
                </div>

                @include('layouts.alert')

                <section class="section">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Daftar Pengguna</h5>
                            <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah User</a>
                        </div>

                        <div class="card-body">

                            <form method="GET" action="{{ route('user.index') }}">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <select name="role" class="form-select" onchange="this.form.submit()">
                                            <option value="">-- Semua Role --</option>
                                            @foreach (['Admin', 'Pelanggan', 'Pengecer'] as $role)
                                                <option value="{{ $role }}"
                                                    {{ request('role') == $role ? 'selected' : '' }}>{{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama, email, nik..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i>
                                            Cari</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary w-100"><i
                                                class="fas fa-sync"></i> Reset</a>
                                    </div>
                                </div>
                            </form>

                            <div class="table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>NIK</th>
                                            <th>Role</th>
                                            <th>Verifikasi</th>
                                            <th>Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                                <td>
                                                    <img src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                                        width="40" height="40" class="rounded-circle">
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->nik }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td>
                                                    @if ($user->verifikasi == 'Verifikasi')
                                                        <span class="badge bg-success">✔️</span>
                                                    @else
                                                        <span class="badge bg-danger">❌</span>
                                                    @endif
                                                </td>
                                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('user.show', $user) }}"
                                                            class="btn btn-sm btn-info m-1" ata-bs-toggle="tooltip"
                                                            title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('user.edit', $user) }}"
                                                            class="btn btn-sm btn-success m-1" ata-bs-toggle="tooltip"
                                                            title="Edit"><i class="fas fa-edit"></i></a>

                                                        <form action="{{ route('user.destroy', $user) }}" method="POST"
                                                            onsubmit="return confirm('Yakin hapus user ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger m-1"
                                                                ata-bs-toggle="tooltip" title="Hapus"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center">Tidak ada data ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <span>
                                    Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari
                                    {{ $users->total() }} data
                                </span>
                                <div>
                                    {{ $users->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                                </div>
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
