<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama' => 'Dishine Bright Glow Serum',
                'deskripsi' => 'Serum pencerah wajah dengan Niacinamide dan Vitamin C untuk kulit lebih cerah alami.',
                'harga_normal' => 95000,
                'harga_reseller' => 75000,
                'stok' => 20,
                'gambar' => 'products/serum.jpg',
            ],
            [
                'nama' => 'Dishine Acne Care Toner',
                'deskripsi' => 'Toner anti jerawat dengan kandungan Salicylic Acid untuk kulit bersih bebas minyak.',
                'harga_normal' => 80000,
                'harga_reseller' => 65000,
                'stok' => 25,
                'gambar' => 'products/toner.jpg',
            ],
            [
                'nama' => 'Dishine Daily Moisturizer',
                'deskripsi' => 'Pelembab ringan dengan SPF 30 untuk menjaga kelembapan dan melindungi dari sinar UV.',
                'harga_normal' => 70000,
                'harga_reseller' => 55000,
                'stok' => 30,
                'gambar' => 'products/moisturizer.jpg',
            ],
            [
                'nama' => 'Dishine Night Repair Cream',
                'deskripsi' => 'Krim malam yang membantu regenerasi kulit selama tidur dengan retinol alami.',
                'harga_normal' => 120000,
                'harga_reseller' => 95000,
                'stok' => 15,
                'gambar' => 'products/nightcream.jpg',
            ],
            [
                'nama' => 'Dishine Lip Serum',
                'deskripsi' => 'Serum bibir lembut dengan kandungan jojoba oil dan vitamin E untuk bibir lembap dan sehat.',
                'harga_normal' => 50000,
                'harga_reseller' => 40000,
                'stok' => 40,
                'gambar' => 'products/lipserum.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
