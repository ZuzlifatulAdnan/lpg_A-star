@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <h3>Detail Pemesanan</h3>
                <p class="text-subtitle text-muted">Lihat detail lengkap pemesanan.</p>
                @include('layouts.alert')
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Pemesanan</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>No Pemesanan</th>
                                <td>{{ $pemesanan->no_pemesanan }}</td>
                            </tr>
                            <tr>
                                <th>Pengguna</th>
                                <td>{{ $pemesanan->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $pemesanan->lokasi->nama_usaha }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>{{ $pemesanan->jumlah }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $pemesanan->status }}</td>
                            </tr>
                            <tr>
                                <th>Total Harga</th>
                                <td>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $pemesanan->catatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat pada</th>
                                <td>{{ $pemesanan->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate pada</th>
                                <td>{{ $pemesanan->updated_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            @if ($pemesanan->pembayaran)
                                <tr>
                                    <th>Status Pembayaran</th>
                                    <td>{{ $pemesanan->pembayaran->status }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Dibayar</th>
                                    <td>Rp {{ number_format($pemesanan->pembayaran->jumlah_dibayar, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </table>

                        <a href="{{ route('pemesanan.index') }}" class="btn btn-warning mt-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
