<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/beranda') }}">
                        <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="img-fluid"
                             style="max-width: 150px; height: auto;">
                    </a>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>

        @if (Auth::check())
            @php
                $role = Auth::user()->role;
            @endphp

            <div class="sidebar-menu">
                <ul class="menu">
                    <li class="sidebar-title">Menu</li>

                    {{-- Semua Role: Beranda --}}
                    <li class="sidebar-item {{ $type_menu == 'beranda' ? 'active' : '' }}">
                        <a href="{{ route('beranda.index') }}" class='sidebar-link'>
                            <i class="bi bi-speedometer2"></i>
                            <span>Beranda</span>
                        </a>
                    </li>

                    {{-- Admin dan Pengecer: Data Lokasi --}}
                    @if ($role == 'Admin')
                        <li class="sidebar-item {{ $type_menu == 'lokasi' ? 'active' : '' }}">
                            <a href="{{ route('lokasi.index') }}" class='sidebar-link'>
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>Data Lokasi</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin & Pangkalan & Pelanggan: Pemesanan --}}
                    @if ($role == 'Admin')
                        <li class="sidebar-item has-sub {{ $type_menu == 'pemesanan' ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-receipt"></i>
                                <span>Pemesanan</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ Request::is('pemesanan') ? 'active' : '' }}">
                                    <a href="{{ route('pemesanan.index') }}">Semua Pemesanan</a>
                                </li>
                                <li class="submenu-item {{ Request::is('pemesanan/diterima') ? 'active' : '' }}">
                                    <a href="{{ route('pemesanan.diterima') }}">Data Diterima</a>
                                </li>
                                <li class="submenu-item {{ Request::is('pemesanan/proses') ? 'active' : '' }}">
                                    <a href="{{ route('pemesanan.proses') }}">Sedang Diproses</a>
                                </li>
                            </ul>
                        </li>
                    @elseif ($role === 'Pelanggan')
                        <li class="sidebar-item {{ $type_menu == 'pemesanan' ? 'active' : '' }}">
                            <a href="{{ route('pemesanan.order') }}" class='sidebar-link'>
                                <i class="bi bi-receipt"></i>
                                <span>Pemesanan</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin: Pembayaran --}}
                    @if ($role == 'Admin')
                        <li class="sidebar-item {{ $type_menu == 'pembayaran' ? 'active' : '' }}">
                            <a href="{{ route('pembayaran.index') }}" class='sidebar-link'>
                                <i class="bi bi-wallet2"></i>
                                <span>Pembayaran</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin & Pangkalan: Stok LPG --}}
                    @if ($role == 'Admin')
                        <li class="sidebar-item {{ $type_menu == 'stok' ? 'active' : '' }}">
                            <a href="{{ route('stok.index') }}" class='sidebar-link'>
                                <i class="bi bi-box-seam"></i>
                                <span>Stok LPG</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin, Pangkalan, Pengecer: Toko Terdekat --}}
                    @if (in_array($role, ['Admin', 'Pelanggan', 'Pengecer']))
                        <li class="sidebar-item {{ $type_menu == 'toko' ? 'active' : '' }}">
                            <a href="{{ route('toko.index') }}" class='sidebar-link'>
                                <i class="bi bi-shop-window"></i>
                                <span>Toko Terdekat</span>
                            </a>
                        </li>
                    @endif

                    {{-- Admin: Pengguna --}}
                    @if ($role === 'Admin')
                        <li class="sidebar-item {{ $type_menu == 'user' ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Pengguna</span>
                            </a>
                        </li>
                    @endif

                    {{-- Pelanggan: Riwayat --}}
                    @if ($role === 'Pelanggan')
                        <li class="sidebar-item {{ $type_menu == 'riwayat' ? 'active' : '' }}">
                            <a href="{{ route('riwayat.index') }}" class='sidebar-link'>
                                <i class="bi bi-clock-history"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </div>
        @endif
    </div>
</div>
