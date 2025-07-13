@extends('layouts.app')

@section('title', 'Users')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Users</h3>
                        <p class="text-subtitle text-muted">Halaman tempat pengguna dapat mengubah informasi users.</p>
                    </div>
                </div>
                @include('layouts.alert')
            </div>

            <!-- Basic Tables start -->
            <section class="section">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Daftar Pengguna</h5>
                        <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah User</a>
                    </div>
                    <div class="card-body">
                        {{-- Filter dan Search --}}
                        <form method="GET" action="{{ route('user.index') }}">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="role" class="form-select" onchange="this.form.submit()">
                                        <option value="">-- Semua Role --</option>
                                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Customer" {{ request('role') == 'Customer' ? 'selected' : '' }}>Customer</option>
                                        <option value="Pangkalan" {{ request('role') == 'Pangkalan' ? 'selected' : '' }}>Pangkalan</option>
                                        <option value="Pengecer" {{ request('role') == 'Pengecer' ? 'selected' : '' }}>Pengecer</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama atau email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary w-100">
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
                                        <th class="text-center">Foto</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Dibuat</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                            <td class="text-center">
                                                <img alt="image"
                                                    src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                                    class="rounded-circle" width="35" height="35"
                                                    data-bs-toggle="tooltip" title="{{ $user->name }}">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('user.edit', $user) }}"
                                                        class="btn btn-sm btn-icon btn-success m-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('user.destroy', $user) }}" method="post"
                                                        onsubmit="this.querySelector('button').disabled = true; return confirm('Yakin ingin menghapus user ini?')">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btn-sm btn-danger btn-icon m-1">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data user ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap mt-3">
                            <span class="text-muted mb-2 mb-md-0">
                                Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} entri
                            </span>
                            <nav class="mb-0">
                                {{ $users->onEachSide(1)->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>

                    </div>
                </div>
            </section>
            <!-- Basic Tables end -->

        </div>
    </div>
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
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
