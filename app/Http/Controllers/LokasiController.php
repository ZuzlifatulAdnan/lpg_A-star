<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'lokasi';

        // ambil data dari tabel lokasi berdasarkan nama jika terdapat request
        $keyword = trim($request->input('name'));

        // Query lokasis dengan filter pencarian dan role
        $lokasis = lokasi::when($keyword, function ($query, $name) {
            $query->where('nama_usaha', 'like', '%' . $name . '%');
        })
            ->latest()
            ->paginate(10);

        // Tambahkan parameter query ke pagination
        $lokasis->appends(['name' => $keyword]);

        // arahkan ke file pages/lokasis/index.blade.php
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
        // validasi data dari form tambah lokasi
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
            'longitude' => $validatedData['longitude'], // Store the image path if available
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
        $users = User::all();

        // arahkan ke file pages/lokasis/edit
        return view('pages.lokasi.edit', compact('lokasi', 'type_menu', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, lokasi $lokasi)
    {
        // Validate the form data
        $request->validate([
            'user_id' => 'required',
            'jenis_usaha' => 'required',
            'nama_usaha' => 'required',
            'alamat' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        // Update the lokasi data
        $lokasi->update([
            'user_id' => $request->user_id,
            'jenis_usaha' => $request->jenis_usaha,
            'nama_usaha' => $request->nama_usaha,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);


        return Redirect::route('lokasi.index')->with('success', 'lokasi ' . $lokasi->nama_usaha . ' berhasil diubah.');
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
