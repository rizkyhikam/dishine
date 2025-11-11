<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Demo',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'alamat' => 'Kota Contoh',
                'no_hp' => '08123456789',
            ]
        );

        // contoh pelanggan
        User::updateOrCreate(
            ['email' => 'pelanggan@example.com'],
            [
                'nama' => 'Pelanggan Demo',
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
                'alamat' => 'Alamat Pelanggan Demo',
                'no_hp' => '081234567890',
            ]
        );
    }
}
