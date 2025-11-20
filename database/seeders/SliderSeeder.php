<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    public function run()
    {
        // Slider 1
        Slider::create([
            'title' => 'Koleksi Lebaran 2025',
            'subtitle' => 'Tampil Elegan di Hari yang Fitri',
            'image' => 'sliders/slider1.jpg', // Pastikan file ini ada atau ganti URL
            'link' => '/katalog',
            'order' => 1,
            'active' => true,
        ]);

        // Slider 2
        Slider::create([
            'title' => 'Diskon Spesial 50%',
            'subtitle' => 'Khusus Member Baru Dishine',
            'image' => 'sliders/slider2.jpg',
            'link' => '/register',
            'order' => 2,
            'active' => true,
        ]);
    }
}