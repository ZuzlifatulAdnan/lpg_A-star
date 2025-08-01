<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\pembayaran;
use App\Models\pemesanan;
use App\Models\stok_lpg;
use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'beranda';

        // Statistik total data
        $users = User::count();
        $lokasis = lokasi::count();
        $pembayarans = pembayaran::count();
        $pemesanans = pemesanan::count();

       // Keyword pencarian NIK
        $keyword = trim($request->input('nik'));
        $keywordLokasi = trim($request->input('lokasi'));

        // Cari user berdasarkan NIK dan role = Pelanggan
        $stoks = User::when($keyword, function ($query, $keyword) {
                $query->where('nik', 'like', "%{$keyword}%");
            })
            ->where('role', 'Pelanggan')
            ->latest()
            ->paginate(9)
            ->appends(['nik' => $keyword]);

        // Query lokasi berdasarkan pencarian lokasi
        $data_lokasis = lokasi::when($keywordLokasi, function ($query, $keywordLokasi) {
            $query->where('nama_usaha', 'like', "%{$keywordLokasi}%")
                ->orWhere('alamat', 'like', "%{$keywordLokasi}%")
                ->orWhere('jenis_usaha', 'like', "%{$keywordLokasi}%")
                ->orWhere('nama_usaha', 'like', "%{$keywordLokasi}%");
        })
            ->paginate(5)
            ->appends([
                'lokasi' => $keywordLokasi
            ]);

        return view('pages.beranda.index', compact(
            'type_menu',
            'users',
            'lokasis',
            'pembayarans',
            'pemesanans',
            'stoks',
            'data_lokasis',
            'keyword',
            'keywordLokasi'
        ));
    }
}
