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
        // Ini akan MENG-UPDATE tabel 'order_items' Anda
        Schema::table('order_items', function (Blueprint $table) {
            
            // Kita tambahkan kolom 'subtotal'
            // Kita taruh setelah 'harga_satuan' agar rapi
            $table->integer('subtotal')->default(0)->after('harga_satuan');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
};