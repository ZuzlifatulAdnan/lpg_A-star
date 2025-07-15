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
                                <td>Rp {{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</td>
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
                                            alt="Bukti Bayar" class="img-thumbnail" style="max-width: 200px; cursor: pointer;"
                                            data-bs-toggle="modal" data-bs-target="#buktiBayarModal">
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

    {{-- Modal Bukti Bayar --}}
    <div class="modal fade" id="buktiBayarModal" tabindex="-1" aria-labelledby="buktiBayarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="buktiBayarModalLabel">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}" alt="Bukti Bayar"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
@endsection
