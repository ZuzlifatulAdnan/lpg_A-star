<?php

namespace App\Http\Controllers;

use App\Models\pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'riwayat';
        $user = Auth::user();

        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'desc');

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        $years = range(date('Y') - 10, date('Y'));
        $statusOptions = ['Proses', 'Selesai', 'Batal'];

        $statusPemesanan = $request->input('status_pemesanan');
        $statusPembayaran = $request->input('status_pembayaran');

        $pemesanans = pemesanan::with('pembayaran')
            ->where('user_id', $user->id)
            ->when($statusPemesanan, fn($q) => $q->where('status', $statusPemesanan))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_order', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal_order', $tahun))
            ->when($statusPembayaran, function ($q) use ($statusPembayaran) {
                if ($statusPembayaran === 'Lunas') {
                    $q->whereHas('pembayaran', fn($q) => $q->where('status', 'Lunas'));
                } elseif ($statusPembayaran === 'Menunggu Pembayaran') {
                    $q->whereHas('pembayaran', fn($q) => $q->where('status', 'Menunggu Pembayaran'))
                        ->orWhereDoesntHave('pembayaran');
                }
            })
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->appends($request->all());

        return view('pages.riwayat.index', compact('type_menu', 'pemesanans', 'months', 'years', 'statusOptions'));
    }

    public function show($id)
    {
        $type_menu = 'riwayat';
        $pemesanan = pemesanan::with(['user', 'pembayaran'])->findOrFail($id);

        // Cek apakah order milik user yang sedang login
        if (Auth::id() !== $pemesanan->user_id) {
            abort(403);
        }

        return view('pages.riwayat.detail', compact('pemesanan', 'type_menu'));
    }
}
