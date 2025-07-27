<?php

namespace App\Console\Commands;

use App\Models\stok_lpg;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoTambahStok extends Command
{
    protected $signature = 'stok:auto-tambah';
    protected $description = 'Menambahkan stok otomatis setiap tanggal 1 awal bulan';

    public function handle()
    {
        $users = User::whereIn('role', ['Pelanggan'])->get();

        foreach ($users as $user) {
            $jumlah = 0;

            if ($user->jenis_pemilik === 'Rumah Tangga') {
                $jumlah = 3;
            } elseif ($user->jenis_pemilik === 'UMKM') {
                $jumlah = 5;
            }

            if ($jumlah > 0) {
                stok_lpg::create([
                    'user_id' => $user->id,
                    'jumlah' => $jumlah,
                    'jenis_pemilik' => $user->jenis_pemilik,
                    'tanggal' => Carbon::now()->toDateString(),
                ]);
            }
        }

        $this->info('Stok berhasil ditambahkan secara otomatis.');
    }
}
