@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('main')
    @if (Auth::user()->role == 'Pelanggan')
        <div id="main-content">
            <div class="page-heading">
                <h3>Selesaikan Pembayaran: {{ $pemesanan->user->name }} - {{ $pemesanan->no_pemesanan }}</h3>
            </div>

            <div class="page-content">
                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('pembayaran.update_user', $pembayaran->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="QRIS"
                                            {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>
                                            QRIS</option>
                                        <option value="Transfer Bank"
                                            {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>
                                            Transfer Bank</option>
                                        <option value="Tunai"
                                            {{ old('metode_pembayaran', $pembayaran->metode_pembayaran) == 'Tunai' ? 'selected' : '' }}>
                                            Tunai</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="qris-section" class="mb-3 d-none">
                                    <img src="{{ asset('img/qris-barcode.png') }}" alt="QRIS" style="max-width:200px;">
                                    <p>Scan barcode untuk QRIS</p>
                                </div>

                                <div id="bank-section" class="mb-3 d-none">
                                    <ul>
                                        <li>Bank BCA - 1234567890 - a.n. PT Contoh</li>
                                        <li>Bank Mandiri - 0987654321 - a.n. PT Contoh</li>
                                        <li>Bank BNI - 1122334455 - a.n. PT Contoh</li>
                                    </ul>
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_dibayar" class="form-label">Jumlah Dibayar</label>
                                    <input type="number" name="jumlah_dibayar" id="jumlah_dibayar" class="form-control"
                                        readonly value="{{ old('jumlah_dibayar', $pembayaran->jumlah_dibayar) }}">
                                </div>

                                <div class="mb-3">
                                    <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
                                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control"
                                        accept="image/*">
                                    @error('bukti_bayar')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <p class="mt-2">Bukti lama:</p>
                                    @if ($pembayaran->bukti_bayar)
                                        <img src="{{ asset('img/bukti_bayar/' . $pembayaran->bukti_bayar) }}"
                                            id="preview-bukti" style="max-width:200px;">
                                    @else
                                        <img src="#" id="preview-bukti" style="max-width:200px; display:none;">
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Perbarui</button>
                                <a href="{{ route('riwayat.index') }}" class="btn btn-warning">Batal</a>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endif
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodeSelect = document.getElementById('metode_pembayaran');
            const qrisSection = document.getElementById('qris-section');
            const bankSection = document.getElementById('bank-section');
            const buktiBayarInput = document.getElementById('bukti_bayar');
            const previewBukti = document.getElementById('preview-bukti');

            function updateMetode() {
                const metode = metodeSelect.value;
                qrisSection.classList.add('d-none');
                bankSection.classList.add('d-none');

                if (metode === 'QRIS') {
                    qrisSection.classList.remove('d-none');
                } else if (metode === 'Transfer Bank') {
                    bankSection.classList.remove('d-none');
                }
            }

            metodeSelect.addEventListener('change', updateMetode);

            buktiBayarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewBukti.src = e.target.result;
                        previewBukti.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewBukti.src = '#';
                    previewBukti.style.display = 'none';
                }
            });

            updateMetode();
        });
    </script>
@endpush
