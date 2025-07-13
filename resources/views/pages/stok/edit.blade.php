@extends('layouts.app')

@section('title', 'Edit Stok LPG')

@push('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
@endpush

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Edit Stok LPG</h3>
                        <p class="text-subtitle text-muted">Halaman untuk mengedit data stok LPG.</p>
                    </div>
                </div>
            </div>

            <section class="section">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('stok.update', $stok->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    {{-- USER --}}
                                    <div class="form-group">
                                        <label for="user_id">User</label>
                                        <select name="user_id" id="user_id" class="form-control" required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $stok->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- JENIS PEMILIK --}}
                                    <div class="form-group">
                                        <label for="jenis_pemilik">Jenis Pemilik</label>
                                        <select name="jenis_pemilik" id="jenis_pemilik" class="form-control" required>
                                            <option value="Admin" {{ $stok->jenis_pemilik == 'Admin' ? 'selected' : '' }}>
                                                Admin</option>
                                            <option value="Pangkalan"
                                                {{ $stok->jenis_pemilik == 'Pangkalan' ? 'selected' : '' }}>Pangkalan</option>
                                            <option value="Pengecer"
                                                {{ $stok->jenis_pemilik == 'Pengecer' ? 'selected' : '' }}>Pengecer</option>
                                        </select>
                                    </div>

                                    {{-- JUMLAH --}}
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah</label>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control"
                                            value="{{ $stok->jumlah }}" required>
                                    </div>

                                    {{-- LOKASI --}}
                                    <div class="form-group">
                                        <label for="lokasi_id">Lokasi</label>
                                        <select name="lokasi_id" id="lokasi_id" class="form-control" required>
                                            @foreach ($lokasi as $lok)
                                                <option value="{{ $lok->id }}"
                                                    {{ $stok->lokasi_id == $lok->id ? 'selected' : '' }}>
                                                    {{ $lok->nama_usaha }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- CATATAN --}}
                                    <div class="form-group">
                                        <label for="catatan">Catatan</label>
                                        <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ $stok->catatan }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new Choices('#user_id', {
                searchEnabled: true,
                itemSelectText: ''
            });
            new Choices('#jenis_pemilik', {
                searchEnabled: false,
                itemSelectText: ''
            });
            new Choices('#lokasi_id', {
                removeItemButton: true,
                searchEnabled: true
            });
        });
    </script>
@endpush
