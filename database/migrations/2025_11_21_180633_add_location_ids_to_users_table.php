<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Cek dulu, kalau BELUM ADA, baru buat
        if (!Schema::hasColumn('users', 'province_id')) {
            $table->string('province_id')->nullable()->after('alamat');
        }
        
        if (!Schema::hasColumn('users', 'city_id')) {
            $table->string('city_id')->nullable()->after('province_id');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
