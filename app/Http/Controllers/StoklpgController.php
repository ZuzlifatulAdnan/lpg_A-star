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

         $stok = stok_lpg::paginate(10); 

        return view('pages.stok.index', compact('type_menu', 'stok'));
    }
    public function create()
    {
        $type_menu = 'stok_lpg';

        return view('pages.stok.create', compact('type_menu'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jumlah' => 'required',
        ]);

        $stok = stok_lpg::create([
            'jumlah' => $validatedData['jumlah'],
        ]);

        return Redirect::route('stok.index')
            ->with('success', 'Stok LPG berhasil ditambahkan Jumlah LPG' . $stok->jumlah . 'untuk Pangkalan');
    }
    public function edit($id)
    {
        $type_menu = 'stok_lpg';

        $stok = stok_lpg::findOrFail($id);

        return view('pages.stok.edit', compact('type_menu', 'stok', 'users'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'jumlah' => 'required',
        ]);

        $stok = stok_lpg::findOrFail($id);

        $stok->update([
            'jumlah' => $validatedData['jumlah'],
        ]);

        return Redirect::route('stok.index')
            ->with('success', 'Stok LPG berhasil Diperbaharui Jumlah LPG' . $stok->jumlah . 'untuk Pangkalan');
    }
}
