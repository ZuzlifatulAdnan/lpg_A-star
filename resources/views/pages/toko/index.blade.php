@extends('layouts.app')

@section('title', 'Toko Terdekat (A*)')

@section('main')
    <div id="main-content">
        <div class="page-heading">
            <h3>Toko Terdekat (A*)</h3>
            <p class="text-muted">Mendeteksi lokasi Anda & mencari rute ke toko terdekat dengan algoritma A*.</p>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if ($errorMessage)
                                <div class="alert alert-danger">{{ $errorMessage }}</div>
                            @elseif (isset($path))
                                <div class="alert alert-success">
                                    <strong>Rute ditemukan:</strong>
                                    <ul>
                                        @foreach ($path as $node)
                                            <li>{{ $node }}</li>
                                        @endforeach
                                    </ul>
                                    <p><strong>Total jarak:</strong> {{ number_format($cost, 2) }} km</p>

                                    @if ($nearest)
                                        <p><strong>Toko:</strong> {{ $nearest->nama_usaha }}</p>
                                        <p><strong>Lokasi:</strong> {{ $nearest->latitude }}, {{ $nearest->longitude }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    Sedang mendeteksi lokasi Anda, mohon tunggu...
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const url = new URL(window.location.href);

            const hasLocation = url.searchParams.has('lat') && url.searchParams.has('lng');
            const hasError = url.searchParams.has('error');

            if (!hasLocation && !hasError) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            url.searchParams.set('lat', lat);
                            url.searchParams.set('lng', lng);

                            window.location.href = url.toString();
                        },
                        (error) => {
                            let errorCode = 'UNKNOWN_ERROR';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    errorCode = 'PERMISSION_DENIED';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorCode = 'POSITION_UNAVAILABLE';
                                    break;
                                case error.TIMEOUT:
                                    errorCode = 'TIMEOUT';
                                    break;
                            }

                            url.searchParams.set('error', errorCode);
                            window.location.href = url.toString();
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                } else {
                    url.searchParams.set('error', 'GEOLOCATION_NOT_SUPPORTED');
                    window.location.href = url.toString();
                }
            }
        });
    </script>
@endsection
