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
        // Ini akan MENG-UPDATE tabel 'payments' Anda
        Schema::table('payments', function (Blueprint $table) {
            
            // Kita tambahkan kolom 'metode_pembayaran'
            // Kita taruh setelah 'status_verifikasi' agar rapi
            $table->string('metode_pembayaran')->nullable()->after('status_verifikasi');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};