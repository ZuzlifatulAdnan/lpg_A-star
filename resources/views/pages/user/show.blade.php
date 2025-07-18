@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('main')
    @if (Auth::user()->role == 'Admin')
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <h3>Detail Pengguna</h3>
                </div>

                <section class="section">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h4 class="card-title">Detail Pengguna</h4>
                                </div>

                                <div class="card-body text-center">
                                    <img src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                        class="rounded-circle mb-3 shadow" width="100" height="100"
                                        alt="Foto {{ $user->name }}">

                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <p class="text-muted">{{ $user->email }}</p>

                                    @if ($user->ktp)
                                        <div class="mb-4">
                                            <h6 class="text-muted">Foto KTP</h6>
                                            <img src="{{ asset('img/user/ktp/' . $user->ktp) }}"
                                                class="img-fluid rounded border shadow-sm" alt="KTP {{ $user->name }}"
                                                style="max-width: 300px;">
                                        </div>
                                    @endif

                                    <table class="table table-bordered mt-4 text-start">
                                        <tr>
                                            <th width="30%">NIK</th>
                                            <td>{{ $user->nik }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $user->alamat }}</td>
                                        </tr>
                                        <tr>
                                            <th>No HP</th>
                                            <td>{{ $user->no_hp }}</td>
                                        </tr>
                                        <tr>
                                            <th>Role</th>
                                            <td>{{ ucfirst($user->role) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Verifikasi</th>
                                            <td>
                                                @if ($user->verifikasi == 'Verifikasi')
                                                    <span class="badge bg-success">✔️ Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-danger">❌ Belum Terverifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Dibuat pada</th>
                                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>

                                    <div class="mt-4 d-flex justify-content-center gap-2">
                                        <a href="{{ route('user.edit', $user) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <a href="{{ route('user.index') }}" class="btn btn-warning">
                                            <i class="fas fa-arrow-left me-1"></i> Kembali
                                        </a>
                                    </div>

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
