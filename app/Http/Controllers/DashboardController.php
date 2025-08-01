<?php

namespace App\Http\Controllers;

use App\Models\stok_lpg;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('name'));

        $stoks = User::when($keyword, function ($query, $keyword) {
            $query->where('nik', 'like', "%{$keyword}%");
        })
            ->where('role', 'Pelanggan')
            ->latest()
            ->paginate(9)
            ->appends(['nik' => $keyword]);

        return view('pages.dashboard.index', compact('stoks', 'keyword'));
    }
}
