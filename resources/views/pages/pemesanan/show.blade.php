@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Detail Pemesanan</h3>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    Detail Pemesanan
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
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
                            <td>{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Catatan</th>
                            <td>{{ $pemesanan->catatan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    Detail Pembayaran
                </div>
                <div class="card-body">
                    @if ($pembayaran)
                        <table class="table table-bordered">
                            <tr>
                                <th>Metode</th>
                                <td>{{ $pembayaran->metode ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Dibayar</th>
                                <td>{{ number_format($pembayaran->jumlah ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $pembayaran->status ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <td>{{ $pembayaran->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('pembayaran.show', $pembayaran->id) }}" class="btn btn-info">
                            Lihat Detail Pembayaran
                        </a>
                    @else
                        <p class="text-muted">Belum ada pembayaran untuk pesanan ini.</p>
                    @endif
                </div>
            </div>

            <a href="{{ route('pemesanan.index') }}" class="btn btn-warning mt-3">Kembali</a>
        </section>
    </div>
@endsection
