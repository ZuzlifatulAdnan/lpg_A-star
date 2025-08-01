@extends('layouts.app')

@section('title', 'Pemesanan Diproses')

@section('main')
    @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pangkalan')
        <div id="main-content">
            <div class="page-heading">
                <h3>Daftar Pemesanan Diterima</h3>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h4>Filter & Pencarian</h4>
                    </div>
                    <div class="card-body">
                        <form class="row g-3 mb-4" method="GET">
                            <div class="col-md-4">
                                <input type="text" name="q" placeholder="Nama atau No Pemesanan"
                                    value="{{ $request->q }}" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                                <a href="{{ route('pemesanan.diterima') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pemesanan</th>
                                        <th>Nama User</th>
                                        <th>Jumlah</th>
                                        <th>Status Pemesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pemesanans as $pemesanan)
                                        <tr>
                                            <td>{{ $loop->iteration + $pemesanans->firstItem() - 1 }}</td>
                                            <td>{{ $pemesanan->no_pemesanan }}</td>
                                            <td>{{ $pemesanan->user->name ?? '-' }}</td>
                                            <td>{{ $pemesanan->jumlah }}</td>
                                            <td>
                                                <span
                                                    class="badge 
                                            @if ($pemesanan->status == 'Diterima') bg-primary
                                            @elseif($pemesanan->status == 'Diproses') bg-warning
                                            @elseif($pemesanan->status == 'Ditunda') bg-danger
                                            @elseif($pemesanan->status == 'Selesai') bg-success
                                            @else bg-secondary @endif">
                                                    {{ $pemesanan->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                           @if (optional($pemesanan->pembayaran)->status == 'Pembayaran Berhasil') bg-success
                                            @elseif(optional($pemesanan->pembayaran)->status == 'Proses Pembayaran') bg-warning
                                            @elseif(optional($pemesanan->pembayaran)->status == 'Menunggu Pembayaran') bg-danger
                                            @else bg-secondary @endif">
                                                    {{ optional($pemesanan->pembayaran)->status ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $pemesanan->created_at->format('d-m-Y') }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalStatus{{ $pemesanan->id }}">
                                                    <i class="fas fa-sync-alt"></i> Ubah
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalStatus{{ $pemesanan->id }}" tabindex="-1"
                                            aria-labelledby="modalStatusLabel{{ $pemesanan->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('pemesanan.updateStatus', $pemesanan->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="modalStatusLabel{{ $pemesanan->id }}">
                                                                Ubah Status Pemesanan
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            {{-- Bukti Bayar --}}
                                                            @if (optional($pemesanan->pembayaran)->bukti_bayar)
                                                                <div class="mb-3 text-center">
                                                                    <a href="{{ asset('img/bukti_bayar/' . $pemesanan->pembayaran->bukti_bayar) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset('img/bukti_bayar/' . $pemesanan->pembayaran->bukti_bayar) }}"
                                                                            alt="Bukti Bayar" class="img-fluid rounded"
                                                                            style="max-height: 200px;">
                                                                    </a>
                                                                    <p class="mt-1"><small>Klik gambar untuk
                                                                            memperbesar</small></p>
                                                                </div>
                                                            @else
                                                                <div class="mb-3 text-center text-muted">
                                                                    <em>Tidak ada bukti bayar.</em>
                                                                </div>
                                                            @endif


                                                            {{-- Status Pemesanan --}}
                                                            <div class="mb-3">
                                                                <label class="form-label">Status Pemesanan</label>
                                                                <select name="status" class="form-select" required>
                                                                    @foreach (['Diterima', 'Selesai'] as $status)
                                                                        <option value="{{ $status }}"
                                                                            {{ $pemesanan->status == $status ? 'selected' : '' }}>
                                                                            {{ $status }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Status Pembayaran --}}
                                                            <div class="mb-3">
                                                                <label class="form-label">Status Pembayaran</label>
                                                                <select name="status_pembayaran" class="form-select"
                                                                    required>
                                                                    @foreach (['Menunggu Pembayaran', 'Proses Pembayaran', 'Pembayaran Berhasil'] as $bayar)
                                                                        <option value="{{ $bayar }}"
                                                                            {{ $pemesanan->status == $bayar ? 'selected' : '' }}>
                                                                            {{ $bayar }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data pemesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $pemesanans->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <div class="alert alert-danger">
            Anda tidak memiliki izin untuk mengakses halaman ini.
        </div>
    @endif
@endsection
