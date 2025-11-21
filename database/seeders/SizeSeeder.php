<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size; // Pastikan namespace ini benar

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Daftar semua ukuran yang dibutuhkan
        $sizes = [
            ['name' => 'S'],
            ['name' => 'M'],
            ['name' => 'L'],
            ['name' => 'XL'],
            ['name' => 'All Size'],
        ];

        // Masukkan data ke database
        foreach ($sizes as $size) {
            Size::create([
                'name' => $size['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}