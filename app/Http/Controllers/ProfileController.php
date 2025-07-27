<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function index()
    {
        $type_menu = 'profile';
        $user = Auth::user()->load('lokasi'); // include relasi
        return view('pages.profile.index', compact('type_menu', 'user'));
    }
    public function edit()
    {
        $type_menu = 'profile';
        $user = Auth::user()->load('lokasi');

        return view('pages.profile.edit', compact('type_menu', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $image = $request->file('file');
        $ktp = $request->file('ktp');

        // Validasi umum (semua role)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|string|unique:users,nik,' . $user->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ktp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Tambahan validasi untuk Admin & Pengecer
        if (in_array($role, ['Admin', 'Pengecer'])) {
            $rules = array_merge($rules, [
                'jenis_usaha' => 'required',
                'nama_usaha' => 'required',
                'alamat_lokasi' => 'required',
                'stok_lpg' => 'required|numeric',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
        }

        $request->validate($rules);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        // Hanya Admin & Pengecer yang boleh update lokasi
        if (in_array($role, ['Admin', 'Pengecer'])) {

            $lokasi = Lokasi::where('user_id', $user->id)->first();

            if ($lokasi) {
                $lokasi->update([
                    'jenis_usaha' => $request->jenis_usaha,
                    'nama_usaha' => $request->nama_usaha,
                    'alamat' => $request->alamat_lokasi,
                    'stok_lpg' => $request->stok_lpg,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            } else {
                Lokasi::create([
                    'user_id' => $user->id,
                    'jenis_usaha' => $request->jenis_usaha,
                    'nama_usaha' => $request->nama_usaha,
                    'alamat' => $request->alamat_lokasi,
                    'stok_lpg' => $request->stok_lpg,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }
        }
        if ($image) {
            $imageName = time() . '_user.' . $image->getClientOriginalExtension();
            $image->move('img/user/', $imageName);

            if ($user->image && file_exists('img/user/' . $user->image)) {
                unlink('img/user/' . $user->image);
            }

            $user->update(['image' => $imageName]);
        }

        if ($ktp) {
            $ktpName = time() . '_ktp.' . $ktp->getClientOriginalExtension();
            $ktp->move('img/user/ktp/', $ktpName);

            if ($user->ktp && file_exists('img/user/ktp/' . $user->ktp)) {
                unlink('img/user/ktp/' . $user->ktp);
            }

            $user->update(['ktp' => $ktpName]);
        }

        return Redirect::route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
    public function show()
    {
        $type_menu = 'profile';
        return view('pages.profile.change-password', compact('type_menu'));
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update the new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')->with('success', 'password berhasil diperbarui.');
    }
}
