<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class StokPengecerController extends Controller
{
     // Menampilkan daftar stok pengecer
    public function index()
    {
        $type_menu = 'stok';

        // Ambil stok milik pengecer yang login
        $stok = lokasi::where('user_id', Auth::id())->paginate(10);

        return view('pages.stok-lpg.index', compact('stok', 'type_menu'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $type_menu = 'stok';
        $stok = lokasi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        return view('pages.stok-lpg.edit', compact('stok', 'type_menu'));
    }

    // Simpan perubahan
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok_lpg' => 'required|numeric|min:0',
        ]);

        $stok = lokasi::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

        $stok->stok_lpg = $request->stok_lpg;
        $stok->save();

        return redirect()->route('stok-lpg.index')->with('success', 'Stok LPG berhasil diperbarui.');
    }
}
