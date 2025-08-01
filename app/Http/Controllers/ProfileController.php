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
        $user = Auth::user(); 
        return view('pages.profile.index', compact('type_menu', 'user'));
    }
    public function edit()
    {
        $type_menu = 'profile';
        $user = Auth::user();

        return view('pages.profile.edit', compact('type_menu', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
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

        $request->validate($rules);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

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
