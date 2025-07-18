<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
         DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'nik' => '1829182',
                'role' =>'Admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'pelanggan',
                'email' => 'pelanggan@gmail.com',
                'password' => Hash::make('12345678'),
                'nik' => '1829182',
                'role' =>'Pelanggan',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'pangkalan',
                'email' => 'pangkalan@gmail.com',
                'password' => Hash::make('12345678'),
                'nik' => '1829182',
                'role' =>'Pangkalan',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'pengecer',
                'email' => 'pengecern@gmail.com',
                'password' => Hash::make('12345678'),
                'nik' => '1829182',
                'role' =>'Pengecer',
                'created_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
