<?php

namespace App\Http\Controllers;

use App\Models\stok_lpg;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('name'));

        $stoks = stok_lpg::with(['user', 'lokasi'])
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('nik', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(9) // agar cocok dengan 3 kolom (3 x 3 grid)
            ->appends(['name' => $keyword]);

        return view('pages.dashboard.index', compact('stoks', 'keyword'));
    }
}
