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
        // Ini memodifikasi tabel 'products' Anda yang LAMA
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom 'category_id'
            $table->foreignId('category_id')
                  ->nullable() // Kita buat nullable (boleh kosong)
                  ->after('stok') // Taruh setelah kolom 'stok'
                  ->constrained('categories') // terhubung ke 'id' di tabel 'categories'
                  ->onDelete('set null'); // Jika kategori dihapus, produknya jadi 'null'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Ini untuk membatalkan migrasi
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};