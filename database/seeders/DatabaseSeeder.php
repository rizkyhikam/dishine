<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder produk dummy
        $this->call([
            ProductSeeder::class,
        ]);

        $this->call(UsersTableSeeder::class);

        // (Opsional) Tambah akun admin / user kalau mau
        // \App\Models\User::factory()->create([
        //     'name' => 'Admin Dishine',
        //     'email' => 'admin@dishine.com',
        // ]);
    }
}
