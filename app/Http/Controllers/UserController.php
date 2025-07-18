<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type_menu = 'user';

        // ambil data dari tabel user berdasarkan nama jika terdapat request
        $keyword = trim($request->input('name'));
        $role = $request->input('role');

        // Query users dengan filter pencarian dan role
        $users = User::when($keyword, function ($query, $name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        // Tambahkan parameter query ke pagination
        $users->appends(['name' => $keyword, 'role' => $role]);

        // arahkan ke file pages/users/index.blade.php
        return view('pages.user.index', compact('type_menu', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $type_menu = 'user';

        // arahkan ke file pages/users/create.blade.php
        return view('pages.user.create', compact('type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data dari form tambah user
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'nik' => 'required|string|unique:users,nik',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'role' => 'required|string',
            'password' => 'required|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'verifikasi' => 'required|string',
            'ktp' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);
        // Handle the image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imagePath);
        }
        $ktpPath = null;
         if ($request->hasFile('ktp')) {
            $ktp = $request->file('ktp');
            $ktpPath = uniqid() . '.' . $ktp->getClientOriginalExtension();
            $ktp->move('img/user/ktp/', $ktpPath);
        }
        //masukan data kedalam tabel users
        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'nik' => $validatedData['nik'],
            'image' => $imagePath, // Store the image path if available
            'alamat' => $validatedData['alamat'],
            'no_hp' => $validatedData['no_hp'],
            'verifikasi' => $validatedData['verifikasi'],
            'ktp' => $ktpPath, 
        ]);

        //jika proses berhsil arahkan kembali ke halaman users dengan status success
        return Redirect::route('user.index')->with('success', 'User ' . $validatedData['name'] . ' berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(User $user)
    {
        $type_menu = 'user';

        // arahkan ke file pages/users/edit
        return view('pages.user.edit', compact('user', 'type_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, User $user)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|string|unique:users,nik,' . $user->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'role' => 'required|string',
            'password' => 'nullable|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'verifikasi' => 'required|string',
            'ktp' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);

        // Update the user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'verifikasi' => $request->verifikasi,
        ]);

        if (!empty($request->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image && file_exists(public_path('img/user/' . $user->image))) {
                unlink(public_path('img/user/' . $user->image));
            }

            // Upload gambar baru
            $image = $request->file('image');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/user/'), $path);

            // Simpan path gambar baru
            $user->update([
                'image' => $path
            ]);
        }

        if ($request->hasFile('ktp')) {
            // Hapus gambar lama jika ada
            if ($user->ktp && file_exists(public_path('img/user/ktp/' . $user->ktp))) {
                unlink(public_path('img/user/ktp/' . $user->imktpage));
            }

            // Upload gambar baru
            $image = $request->file('ktp');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/user/ktp/'), $path);

            // Simpan path gambar baru
            $user->update([
                'ktp' => $path
            ]);
        }
        return Redirect::route('user.index')->with('success', 'User ' . $user->name . ' berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Hapus bukti bayar jika ada
        if ($user->image && file_exists(public_path('img/user/' . $user->image))) {
            unlink(public_path('img/user/' . $user->image));
        }
        if ($user->ktp && file_exists(public_path('img/user/ktp/' . $user->ktp))) {
            unlink(public_path('img/user/ktp/' . $user->ktp));
        }
        $user->delete();
        return Redirect::route('user.index')->with('success', 'User ' . $user->name . ' berhasil di hapus.');
    }
    public function show(User $user)
    {
        $type_menu = 'user';
        return view('pages.user.show', compact('user', 'type_menu'));
    }

}
