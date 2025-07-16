<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\pembayaran;
use App\Models\pemesanan;
use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $type_menu = 'beranda';
        $users = User::count();
        $lokasis = lokasi::count();
        $pembayarans = pembayaran::count();
        $pemesanans = pemesanan::count();

        $data_lokasis = Lokasi::paginate(5); // tampilkan 5 per halaman

        return view('pages.beranda.index', compact(
            'type_menu',
            'users',
            'lokasis',
            'pembayarans',
            'pemesanans',
            'data_lokasis'
        ));

    }
}
