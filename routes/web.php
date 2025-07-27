<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\StoklpgController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::resource('dashboard', DashboardController::class);

Route::middleware(['auth'])->group(function () {
    // beranda
    Route::resource('beranda', BerandaController::class);
    // lokasi
    Route::resource('lokasi', LokasiController::class);
    // pembayaran
    Route::get('/pembayaran/{id}/edit-user', [PembayaranController::class, 'editUser'])->name('pembayaran.edit_user');
    Route::put('/pembayaran/{id}/edit-user', [PembayaranController::class, 'updateUser'])->name('pembayaran.update_user');
    Route::resource('pembayaran', PembayaranController::class);
    // pemesanan
    Route::get('/pemesanan/order', [PemesananController::class, 'order'])->name('pemesanan.order');
    Route::post('/pemesanan/order', [PemesananController::class, 'storeOrder'])->name('pemesanan.storeOrder');
    Route::get('/pemesanan/proses', [PemesananController::class, 'showProses'])->name('pemesanan.proses');
    Route::put('/pemesanan/update-status/{id}', [PemesananController::class, 'updateStatus'])->name('pemesanan.updateStatus');
    Route::get('/pemesanan/diterima', [PemesananController::class, 'showDiterima'])->name('pemesanan.diterima');
    Route::resource('pemesanan', PemesananController::class);
    // riwayat
    Route::resource('riwayat', RiwayatController::class);
    // stok
    Route::post('/stok/manual', [StokLpgController::class, 'manualTambah'])->name('stok.manual');
    Route::resource('stok', StoklpgController::class);
    // toko
    Route::resource('toko', TokoController::class);
    // User
    Route::resource('user', UserController::class);
    // Profile
    Route::resource('profile', ProfileController::class);
    Route::post('/profile/update/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/change-password/{user}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});
