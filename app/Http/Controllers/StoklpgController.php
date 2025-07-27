<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\stok_lpg;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class StoklpgController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'stok';
        $search = $request->input('search');
        $jenis = $request->input('jenis_pemilik');
        $user = Auth::user();

        $query = stok_lpg::with('user', 'lokasi');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($jenis) {
            $query->where('jenis_pemilik', $jenis);
        }

        $stokUsers = $query->latest()->paginate(10)->withQueryString();
        $jenisList = stok_lpg::select('jenis_pemilik')->distinct()->pluck('jenis_pemilik');

        return view('pages.stok.index', compact('type_menu', 'stokUsers', 'jenisList', 'search', 'jenis'));
    }
    public function create()
    {
        $type_menu = 'stok_lpg';

        $users = User::all();
        $lokasi = lokasi::all();

        // arahkan ke file pages/stok/create.blade.php
        return view('pages.stok.create', compact('type_menu', 'users', 'lokasi'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_pemilik' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi_id' => 'required|exists:lokasis,id',
            'catatan' => 'nullable|string',
        ]);

        $stok = stok_lpg::create([
            'user_id' => $validatedData['user_id'],
            'jenis_pemilik' => $validatedData['jenis_pemilik'],
            'jumlah' => $validatedData['jumlah'],
            'lokasi_id' => $validatedData['lokasi_id'],
            'catatan' => $validatedData['catatan'],
        ]);

        return Redirect::route('stok.index')
            ->with('success', 'Stok LPG berhasil ditambahkan untuk user ' . $stok->user->name . 'dan stok jenis' . $stok->jenis_pemilik);
    }
    public function edit($id)
    {
        $type_menu = 'stok_lpg';

        $stok = stok_lpg::findOrFail($id);
        $users = User::all();
        $lokasi = lokasi::all();

        return view('pages.stok.edit', compact('type_menu', 'stok', 'users', 'lokasi'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_pemilik' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi_id' => 'required|exists:lokasis,id',
            'catatan' => 'nullable|string',
        ]);

        $stok = stok_lpg::findOrFail($id);

        $user = User::findOrFail($validatedData['user_id']);
        $lokasi = lokasi::findOrFail($validatedData['lokasi_id']);

        $stok->update([
            'user_id' => $validatedData['user_id'],
            'jenis_pemilik' => $validatedData['jenis_pemilik'],
            'jumlah' => $validatedData['jumlah'],
            'lokasi_id' => $validatedData['lokasi_id'],
            'catatan' => $validatedData['catatan'],
        ]);

        return Redirect::route('stok.index', $validatedData['user_id'])
            ->with('success', 'Stok LPG untuk user' . $user->name . ' pada lokasi ' . $lokasi->nama_usaha . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = stok_lpg::findOrFail($id);

        $user = $stok->user;
        $lokasi = $stok->lokasi;

        $stok->delete();

        return Redirect::route('stok.index')
            ->with('success', 'Stok LPG untuk user ' . $user->name . ' pada lokasi ' . $lokasi->nama_usaha . ' berhasil dihapus.');
    }
    public function manualTambah()
    {
        $lokasi = lokasi::first(); // Ambil lokasi default, atau sesuaikan jika perlu

        if (!$lokasi) {
            return redirect()->route('stok.index')->with('error', 'Lokasi tidak ditemukan.');
        }

        $users = User::where('role', 'Pelanggan')->get();
        $berhasil = 0;

        foreach ($users as $user) {
            // Ambil jenis pemilik dari data stok terakhir user
            $latestStok = stok_lpg::where('user_id', $user->id)->latest()->first();
            $jenis = $latestStok->jenis_pemilik ?? 'Rumah Tangga';

            // Tentukan jumlah berdasarkan jenis pemilik
            $jumlah = match ($jenis) {
                'UMKM' => 5,
                'Rumah Tangga' => 3,
                default => 3,
            };

            // Cek apakah stok untuk hari ini sudah ada
            $stokHariIni = stok_lpg::where('user_id', $user->id)
                ->whereDate('created_at', now()->toDateString())
                ->first();

            if ($stokHariIni) {
                // Update stok jika sudah ada
                $stokHariIni->update([
                    'jumlah' => $jumlah,
                    'catatan' => 'Stok diperbarui manual',
                ]);
                $berhasil++;
            } else {
                // Tambahkan stok baru
                stok_lpg::create([
                    'user_id' => $user->id,
                    'jumlah' => $jumlah,
                    'jenis_pemilik' => $jenis,
                    'lokasi_id' => $lokasi->id,
                    'catatan' => 'Tambah stok manual otomatis',
                ]);
                $berhasil++;
            }
        }

        return redirect()->route('stok.index')->with('success', "$berhasil stok berhasil ditambahkan atau diperbarui secara manual untuk pelanggan.");
    }


}
