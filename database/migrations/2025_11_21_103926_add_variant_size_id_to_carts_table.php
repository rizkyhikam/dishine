<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Menambah kolom variant_size_id
            $table->unsignedBigInteger('variant_size_id')
                  ->nullable() // Penting: nullable karena mungkin ada produk lama
                  ->after('product_id'); 
            
            // Catatan: Jika Anda ingin menggunakan foreign key:
            // $table->foreign('variant_size_id')->references('id')->on('variant_sizes'); 
            // Namun, karena kolom ini bisa merujuk ke dua tabel, 
            // lebih aman membiarkannya tanpa foreign key constraint.
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('variant_size_id');
        });
    }
};
