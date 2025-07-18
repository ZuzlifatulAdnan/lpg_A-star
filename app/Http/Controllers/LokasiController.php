<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'lokasi';
        $authUser = Auth::user();

        $keyword = trim($request->input('name'));

        // Query awal
        $lokasis = lokasi::query();

        // Jika user adalah pangkalan, filter berdasarkan user_id
        if ($authUser->role == 'Pangkalan') {
            $lokasis->where('user_id', $authUser->id);
        }

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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'lokasi';
        $users = User::all();

        // arahkan ke file pages/lokasis/create.blade.php
        return view('pages.lokasi.create', compact('type_menu', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'jenis_usaha' => 'required',
            'nama_usaha' => 'required',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        //masukan data kedalam tabel lokasis
        lokasi::create([
            'user_id' => $validatedData['user_id'],
            'jenis_usaha' => $validatedData['jenis_usaha'],
            'nama_usaha' => $validatedData['nama_usaha'],
            'alamat' => $validatedData['alamat'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
        ]);

        //jika proses berhsil arahkan kembali ke halaman lokasis dengan status success
        return Redirect::route('lokasi.index')->with('success', 'lokasi ' . $validatedData['nama_usaha'] . ' berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(lokasi $lokasi)
    {
        $type_menu = 'lokasi';
        $authUser = Auth::user();

        // Jika role adalah admin, tampilkan semua user
        if ($authUser->role === 'Admin') {
            $users = User::all();
        }
        // Jika role adalah pangkalan atau pengecer, hanya tampilkan user yang sedang login
        else {
            $users = collect([$authUser]); // gunakan collection agar kompatibel di view
        }

        return view('pages.lokasi.edit', compact('lokasi', 'type_menu', 'users'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, lokasi $lokasi)
    {
        $authUser = Auth::user();

        if ($authUser->role === 'Admin') {
            // Validasi untuk Admin
            $request->validate([
                'user_id' => 'required',
                'jenis_usaha' => 'required',
                'nama_usaha' => 'required',
                'alamat' => 'required',
                'latitude' => 'required',
                'longitude' => 'required'
            ]);

            // Update data lokasi untuk Admin
            $lokasi->update([
                'user_id' => $request->user_id,
                'jenis_usaha' => $request->jenis_usaha,
                'nama_usaha' => $request->nama_usaha,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

        } elseif (in_array($authUser->role, ['Pangkalan', 'Pengecer'])) {
            // Validasi untuk Pangkalan dan Pengecer
            $request->validate([
                'jenis_usaha' => 'required',
                'nama_usaha' => 'required',
                'alamat' => 'required',
                'latitude' => 'required',
                'longitude' => 'required'
            ]);

            // Update data lokasi untuk Pangkalan dan Pengecer
            $lokasi->update([
                'user_id' => $authUser->id,
                'jenis_usaha' => $request->jenis_usaha,
                'nama_usaha' => $request->nama_usaha,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

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
