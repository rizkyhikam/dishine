<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // 1. Cek & Tambah 'nama_penerima'
            if (!Schema::hasColumn('orders', 'nama_penerima')) {
                $table->string('nama_penerima')->nullable()->after('user_id');
            }

            // 2. Cek & Tambah 'no_hp'
            if (!Schema::hasColumn('orders', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('nama_penerima');
            }

            // 3. Cek & Tambah 'alamat_pengiriman'
            if (!Schema::hasColumn('orders', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('no_hp');
            }

            // 4. Cek & Tambah 'total_harga' (Grand Total)
            // Kadang ini juga hilang jika migrasi awal tidak lengkap
            if (!Schema::hasColumn('orders', 'total_harga')) {
                $table->decimal('total_harga', 15, 2)->default(0)->after('biaya_layanan'); 
                // Note: biaya_layanan ditambahkan di migrasi sebelumnya, jadi aman taruh sesudahnya
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama_penerima', 'no_hp', 'alamat_pengiriman', 'total_harga']);
        });
    }
};