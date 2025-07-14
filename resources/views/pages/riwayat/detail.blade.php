@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <h3>Detail Pemesanan</h3>
                <p class="text-subtitle text-muted">Rincian pesanan Anda.</p>
            </div>

            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('riwayat.index') }}" class="btn btn-warning mb-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                        <table class="table table-bordered">
                            <tr>
                                <th>Tanggal Order</th>
                                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal_order)->format('d-m-Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @switch($pemesanan->status)
                                        @case('Selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @break

                                        @case('Diterima')
                                            <span class="badge bg-primary">Diterima</span>
                                        @break

                                        @case('Diproses')
                                            <span class="badge bg-warning text-dark">Diproses</span>
                                        @break

                                        @case('Ditunda')
                                            <span class="badge bg-secondary">Ditunda</span>
                                        @break

                                        @default
                                            <span class="badge bg-light text-dark">{{ $pemesanan->status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>Total Biaya</th>
                                <td>Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $pemesanan->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pembayaran</th>
                                <td>
                                    @if ($pemesanan->pembayaran)
                                        <strong>Metode:</strong> {{ $pemesanan->pembayaran->metode_pembayaran }} <br>
                                        <strong>Jumlah:</strong>
                                        Rp{{ number_format($pemesanan->pembayaran->jumlah_dibayar, 0, ',', '.') }} <br>
                                        <strong>Status:</strong> {{ $pemesanan->pembayaran->status }} <br>

                                        @if ($pemesanan->pembayaran->bukti_bayar)
                                            <strong>Bukti Bayar:</strong><br>
                                            <img src="{{ asset('img/bukti_bayar/' . $pemesanan->pembayaran->bukti_bayar) }}"
                                                alt="Bukti Bayar"
                                                style="max-width: 200px; margin-top: 10px; cursor:pointer;"
                                                class="img-thumbnail" data-bs-toggle="modal"
                                                data-bs-target="#buktiBayarModal">
                                        @else
                                            <em>Tidak ada bukti bayar.</em>
                                        @endif
                                    @else
                                        <em>Belum ada pembayaran</em>
                                    @endif
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </section>
        </div>
    </div>

    @if ($pemesanan->pembayaran && $pemesanan->pembayaran->bukti_bayar)
        <!-- Modal Bukti Bayar -->
        <div class="modal fade" id="buktiBayarModal" tabindex="-1" aria-labelledby="buktiBayarModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiBayarModalLabel">Bukti Bayar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('img/bukti_bayar/' . $pemesanan->pembayaran->bukti_bayar) }}" alt="Bukti Bayar"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
