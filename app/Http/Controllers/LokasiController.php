<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'lokasi';
        $authUser = Auth::user();

        $keyword = trim($request->input('name'));

        // Query awal
        $lokasis = lokasi::query();

        // Filter berdasarkan nama usaha jika keyword tersedia
        if (!empty($keyword)) {
            $lokasis->where('nama_usaha', 'like', '%' . $keyword . '%');
        }

        // Urutkan dan paginasi
        $lokasis = $lokasis->latest()->paginate(10);

        // Tambahkan parameter pencarian ke pagination
        $lokasis->appends(['name' => $keyword]);

        return view('pages.lokasi.index', compact('type_menu', 'lokasis'));
    }

    public function create()
    {
        $type_menu = 'lokasi';
        $users = User::whereIn('role', ['Admin', 'Pengecer'])->get();
        // arahkan ke file pages/lokasis/create.blade.php
        return view('pages.lokasi.create', compact('type_menu', 'users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'jenis_usaha' => 'required',
            'nama_usaha' => 'required',
            'alamat' => 'required',
            'stok_lpg' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        //masukan data kedalam tabel lokasis
        lokasi::create([
            'user_id' => $validatedData['user_id'],
            'jenis_usaha' => $validatedData['jenis_usaha'],
            'nama_usaha' => $validatedData['nama_usaha'],
            'alamat' => $validatedData['alamat'],
            'stok_lpg' => $validatedData['stok_lpg'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
        ]);

        //jika proses berhsil arahkan kembali ke halaman lokasis dengan status success
        return Redirect::route('lokasi.index')->with('success', 'lokasi ' . $validatedData['nama_usaha'] . ' berhasil ditambah.');
    }

    public function edit(lokasi $lokasi)
    {
        $type_menu = 'lokasi';
        $users = User::whereIn('role', ['Admin', 'Pengecer'])->get();

        return view('pages.lokasi.edit', compact('lokasi', 'type_menu', 'users'));
    }

    public function update(Request $request, lokasi $lokasi)
    {
        $request->validate([
            'user_id' => 'required',
            'jenis_usaha' => 'required',
            'nama_usaha' => 'required',
            'alamat' => 'required',
            'stok_lpg' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        // Update data lokasi untuk Admin
        $lokasi->update([
            'user_id' => $request->user_id,
            'jenis_usaha' => $request->jenis_usaha,
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'stok_lpg' => $request->stok_lpg,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return Redirect::route('lokasi.index')->with('success', 'Lokasi ' . $lokasi->nama_usaha . ' berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(lokasi $lokasi)
    {
        $lokasi->delete();
        return Redirect::route('lokasi.index')->with('success', 'lokasi ' . $lokasi->nama_usaha . ' berhasil di hapus.');
    }
    public function show(lokasi $lokasi)
    {
        $type_menu = 'lokasi';
        return view('pages.lokasi.show', compact('lokasi', 'type_menu'));
    }
}
