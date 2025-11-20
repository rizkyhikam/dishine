<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('sliders', 'alt')) {
                $table->string('alt')->nullable()->after('image');
            }

            if (!Schema::hasColumn('sliders', 'position')) {
                $table->integer('position')->default(0)->after('alt');
            }

            if (!Schema::hasColumn('sliders', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('position');
            }

            // Hapus kolom lama jika ada
            foreach (['title', 'subtitle', 'link', 'order', 'active'] as $col) {
                if (Schema::hasColumn('sliders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Kembalikan (opsional)
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('link')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);

            $table->dropColumn(['alt', 'position', 'is_active']);
        });
    }
};
