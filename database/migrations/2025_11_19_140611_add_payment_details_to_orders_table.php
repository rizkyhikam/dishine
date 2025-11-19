<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // 1. PERBAIKAN UTAMA: Cek dulu apakah 'metode_pembayaran' ada?
            // Jika tidak ada, kita buatkan sekarang agar tidak error.
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                // Kita taruh setelah status (karena status biasanya sudah ada)
                $table->string('metode_pembayaran')->default('transfer')->after('status');
            }

            // 2. Lanjut tambahkan kolom lainnya
            if (!Schema::hasColumn('orders', 'tanggal_pesan')) {
                $table->dateTime('tanggal_pesan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('tanggal_pesan');
            }
            if (!Schema::hasColumn('orders', 'ongkir')) {
                $table->decimal('ongkir', 15, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('orders', 'biaya_layanan')) {
                $table->decimal('biaya_layanan', 15, 2)->default(0)->after('ongkir');
            }
            
            // Sekarang aman menggunakan after('metode_pembayaran') karena sudah dipastikan ada di poin 1
            if (!Schema::hasColumn('orders', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            }
            
            // Info Kurir
            if (!Schema::hasColumn('orders', 'kurir')) {
                // Cek referensi 'alamat_pengiriman', kalau tidak ada taruh setelah id
                $after = Schema::hasColumn('orders', 'alamat_pengiriman') ? 'alamat_pengiriman' : 'id';
                $table->string('kurir')->nullable()->after($after);
            }
            if (!Schema::hasColumn('orders', 'layanan_kurir')) {
                $table->string('layanan_kurir')->nullable()->after('kurir');
            }
            if (!Schema::hasColumn('orders', 'kota_tujuan')) {
                $table->string('kota_tujuan')->nullable()->after('layanan_kurir');
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pembayaran', // Hapus juga jika rollback
                'tanggal_pesan', 'total', 'ongkir', 'biaya_layanan', 
                'bukti_pembayaran', 'kurir', 'layanan_kurir', 'kota_tujuan'
            ]);
        });
    }
};