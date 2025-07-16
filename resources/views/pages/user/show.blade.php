@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <h3>Detail Pengguna</h3>
            </div>

            <section class="section">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Pengguna</h4>
                            </div>

                            <div class="card-body text-center">
                                <img src="{{ $user->image ? asset('img/user/' . $user->image) : asset('assets/compiled/jpg/2.jpg') }}"
                                    class="rounded-circle mb-3" width="100" height="100"
                                    alt="Foto {{ $user->name }}">

                                <h5>{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>

                                <table class="table table-bordered mt-3 text-start">
                                    <tr>
                                        <th>NIK</th>
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
                                        <td>{{ $user->role }}</td>
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

                                <div class="mt-3">
                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('user.index') }}" class="btn btn-warning">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
