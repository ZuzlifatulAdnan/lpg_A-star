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

        $query = stok_lpg::query()
            ->select(
                'user_id',
                DB::raw('MAX(id) as stok_id'),
                DB::raw('MAX(jenis_pemilik) as jenis_pemilik'),
                DB::raw('SUM(jumlah) as total_jumlah')
            )
            ->with('user')
            ->groupBy('user_id');

        if ($user->role === 'Admin') {
            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            if ($jenis) {
                $query->where('jenis_pemilik', $jenis);
            }
        } elseif ($user->role === 'Pangkalan') {
            // Ambil lokasi milik user
            $lokasi = lokasi::where('user_id', $user->id)->first(); // pakai first() karena 1 user = 1 lokasi

            if ($lokasi) {
                $query->where('lokasi_id', $lokasi->id);
            }

            if ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            if ($jenis) {
                $query->where('jenis_pemilik', $jenis);
            }
        } else {
            // Untuk role lain, bisa disesuaikan atau kosongkan
            $query->whereRaw('1 = 0'); // supaya tidak ada data ditampilkan
        }

        $stokUsers = $query->paginate(10)->withQueryString();

        $jenisList = stok_lpg::select('jenis_pemilik')->distinct()->pluck('jenis_pemilik');

        return view('pages.stok.index', compact(
            'type_menu',
            'stokUsers',
            'jenisList',
            'search',
            'jenis'
        ));
    }

    public function create()
    {
        $type_menu = 'stok_lpg';

        $users = collect();
        $lokasi = collect();
        $user = Auth::user();

        if ($user->role === 'Pangkalan') {
            // Ambil semua user dengan role Pelanggan
            $users = User::where('role', 'Pelanggan')->get();

            // Ambil lokasi milik user yang sedang login (pangkalan)
            $lokasi = lokasi::where('user_id', $user->id)->get();
        } elseif ($user->role === 'Admin') {
            // Jika admin, ambil semua user dan lokasi
            $users = User::all();
            $lokasi = lokasi::all();
        }

        // arahkan ke file pages/stok/create.blade.php
        return view('pages.stok.create', compact('type_menu', 'users', 'lokasi'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_pemilik' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'lokasi_id' => 'required|array',
            'lokasi_id.*' => 'exists:lokasis,id',
            'catatan' => 'nullable|string',
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $lokasiNames = [];

        DB::transaction(function () use ($validatedData, &$lokasiNames) {
            foreach ($validatedData['lokasi_id'] as $lokasiId) {
                $lokasi = lokasi::findOrFail($lokasiId);
                $lokasiNames[] = $lokasi->nama_usaha;

                stok_lpg::create([
                    'user_id' => $validatedData['user_id'],
                    'jenis_pemilik' => $validatedData['jenis_pemilik'],
                    'jumlah' => $validatedData['jumlah'],
                    'lokasi_id' => $lokasiId,
                    'catatan' => $validatedData['catatan'],
                ]);
            }
        });

        return Redirect::route('stok.index')
            ->with('success', 'Stok LPG berhasil ditambahkan untuk user ' . $user->name . ' pada lokasi: ' . implode(', ', $lokasiNames));
    }


    public function edit($id)
    {
        $type_menu = 'stok_lpg';

        $stok = stok_lpg::findOrFail($id);
        $users = collect();
        $lokasi = collect();
        $user = Auth::user();

        if ($user->role === 'Pangkalan') {
            // Ambil semua user dengan role Pelanggan
            $users = User::where('role', 'Pelanggan')->get();

            // Ambil lokasi milik user yang sedang login (pangkalan)
            $lokasi = lokasi::where('user_id', $user->id)->get();
        } elseif ($user->role === 'Admin') {
            // Jika admin, ambil semua user dan lokasi
            $users = User::all();
            $lokasi = lokasi::all();
        }

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

        return Redirect::route('stok.show', $validatedData['user_id'])
            ->with('success', 'Stok LPG untuk user' . $user->name . ' pada lokasi ' . $lokasi->nama_usaha . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $stok = stok_lpg::findOrFail($id);

        $user = $stok->user;
        $lokasi = $stok->lokasi;

        $stok->delete();

        return Redirect::route('stok.show', $user->id)
            ->with('success', 'Stok LPG untuk user' . $user->name . 'pada lokasi ' . $lokasi->nama_usaha . ' berhasil dihapus.');
    }

    public function show($user_id)
    {
        $type_menu = 'stok_lpg';

        $user = User::findOrFail($user_id);

        $stokList = stok_lpg::with('lokasi')
            ->where('user_id', $user_id)->paginate(10)->withQueryString();

        return view('pages.stok.show', compact('type_menu', 'user', 'stokList'));
    }
}
