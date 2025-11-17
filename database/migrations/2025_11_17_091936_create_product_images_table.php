<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            
            // Ini adalah "kunci" untuk menghubungkan ke tabel 'products'
            $table->foreignId('product_id')
                  ->constrained('products') // terhubung ke 'id' di tabel 'products'
                  ->onDelete('cascade'); // Jika produk dihapus, foto-fotonya ikut terhapus

            // Ini adalah path ke gambar (e.g., 'products/gallery/tunik_1.jpg')
            $table->string('path'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};