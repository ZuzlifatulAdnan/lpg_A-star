<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $type_menu = 'beranda';
        $users = User::all();
        return view('pages.beranda.index', compact(
            'type_menu',
            'users',
        ));
    }
}
