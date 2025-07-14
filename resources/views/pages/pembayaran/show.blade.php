@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Detail Pembayaran</h3>
        </div>

        <div class="page-content">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>No Pembayaran</th>
                                <td>{{ $pembayaran->no_pembayaran }}</td>
                            </tr>
                            <tr>
                                <th>Nama Pemesan</th>
                                <td>{{ $pembayaran->pemesanan->user->name }}</td>
                            </tr>
                            <tr>
                                <th>No Pemesanan</th>
                                <td>{{ $pembayaran->pemesanan->no_pemesanan }}</td>
                            </tr>
                            <tr>
                                <th>Metode</th>
                                <td>{{ $pembayaran->metode_pembayaran }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Dibayar</th>
                                <td>{{ number_format($pembayaran->jumlah_dibayar) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $pembayaran->status }}</td>
                            </tr>
                            <tr>
                                <th>Bukti Bayar</th>
                                <td>
                                    @if ($pembayaran->bukti_bayar)
                                        <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}"
                                            style="max-width:200px;">
                                    @else
                                        Tidak ada
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-warning">Kembali</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
