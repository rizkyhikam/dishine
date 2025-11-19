<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita cek dulu biar tidak error kalau kolom sudah ada
            if (!Schema::hasColumn('users', 'province_id')) {
                $table->string('province_id')->nullable()->after('alamat');
                $table->string('city_id')->nullable()->after('province_id');
                $table->string('district_id')->nullable()->default(0)->after('city_id'); // Set default 0 krn manual
                $table->string('postal_code')->nullable()->after('district_id');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['province_id', 'city_id', 'district_id', 'postal_code']);
        });
    }
};