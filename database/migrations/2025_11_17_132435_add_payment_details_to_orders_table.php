<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * VERSI PERBAIKAN (HANYA MENAMBAHKAN YANG HILANG)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // --- 2 BARIS INI KITA HAPUS KARENA SUDAH ADA DI DATABASE ---
            // $table->integer('biaya_layanan')->default(0)->after('ongkir');
            // $table->integer('total_bayar')->default(0)->after('biaya_layanan');
            
            // --- KITA HANYA JALANKAN 3 KOLOM YANG PASTI HILANG ---
            $table->string('kurir')->nullable()->after('alamat_pengiriman'); 
            $table->string('layanan_kurir')->nullable()->after('kurir');
            $table->string('kota_tujuan')->nullable()->after('layanan_kurir');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Sesuaikan dengan fungsi up()
            $table->dropColumn([
                'kurir',
                'layanan_kurir',
                'kota_tujuan'
                // Kita tidak perlu menghapus 'biaya_layanan' 
                // atau 'total_bayar' karena file ini tidak membuatnya
            ]);
        });
    }
};