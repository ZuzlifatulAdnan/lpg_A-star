@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Tambah Pembayaran</h3>
        </div>

        <div class="page-content">
            <section class="section">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('pembayaran.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Pemesanan --}}
                            <div class="mb-3">
                                <label for="pemesanan_id" class="form-label">Pemesanan</label>
                                <select name="pemesanan_id" id="pemesanan_id"
                                    class="form-select @error('pemesanan_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pemesanan --</option>
                                    @foreach ($pemesanan_list as $pemesanan)
                                        <option value="{{ $pemesanan->id }}" data-total="{{ $pemesanan->total_harga }}"
                                            {{ old('pemesanan_id') == $pemesanan->id ? 'selected' : '' }}>
                                            {{ $pemesanan->user->name }} - {{ $pemesanan->no_pemesanan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pemesanan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran"
                                    class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS
                                    </option>
                                    <option value="Transfer Bank"
                                        {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank
                                    </option>
                                    <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai
                                    </option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tambahan Dinamis --}}
                            <div id="qris-section" class="mb-3 d-none">
                                <label class="form-label">QRIS</label><br>
                                <img src="{{ asset('img/qris-barcode.png') }}" alt="QRIS Barcode" style="max-width:200px;">
                                <p>Scan barcode di atas untuk pembayaran via QRIS.</p>
                            </div>

                            <div id="bank-section" class="mb-3 d-none">
                                <label class="form-label">Rekening Bank</label>
                                <ul>
                                    <li>Bank BCA - 1234567890 - a.n. PT Contoh</li>
                                    <li>Bank Mandiri - 0987654321 - a.n. PT Contoh</li>
                                    <li>Bank BNI - 1122334455 - a.n. PT Contoh</li>
                                </ul>
                            </div>

                            {{-- Jumlah Dibayar --}}
                            <div class="mb-3">
                                <label for="jumlah_dibayar" class="form-label">Jumlah Dibayar</label>
                                <input type="number" name="jumlah_dibayar" id="jumlah_dibayar"
                                    value="{{ old('jumlah_dibayar') }}"
                                    class="form-control @error('jumlah_dibayar') is-invalid @enderror" readonly required>
                                @error('jumlah_dibayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bukti Bayar --}}
                            <div class="mb-3">
                                <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
                                <input type="file" name="bukti_bayar" id="bukti_bayar"
                                    class="form-control @error('bukti_bayar') is-invalid @enderror" accept="image/*"
                                    required>
                                @error('bukti_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Preview --}}
                                <div class="mt-3">
                                    <img id="preview-bukti" src="#" alt="Preview Bukti Bayar"
                                        style="max-width:200px; display:none;" />
                                </div>
                            </div>

                            {{-- Tombol --}}
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-warning">Batal</a>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Script Dinamis --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pemesananSelect = document.getElementById('pemesanan_id');
                const jumlahInput = document.getElementById('jumlah_dibayar');
                const metodeSelect = document.getElementById('metode_pembayaran');
                const qrisSection = document.getElementById('qris-section');
                const bankSection = document.getElementById('bank-section');
                const buktiBayarInput = document.getElementById('bukti_bayar');
                const previewBukti = document.getElementById('preview-bukti');

                function updateJumlah() {
                    const selected = pemesananSelect.options[pemesananSelect.selectedIndex];
                    const total = selected.getAttribute('data-total');
                    jumlahInput.value = total ?? '';
                }

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

                pemesananSelect.addEventListener('change', updateJumlah);
                metodeSelect.addEventListener('change', updateMetode);

                // image preview
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

                // panggil saat load jika sudah ada old value
                updateJumlah();
                updateMetode();
            });
        </script>
    @endpush
@endsection
