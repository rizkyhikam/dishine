<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    public function up()
{
    Schema::create('sliders', function (Blueprint $table) {
        $table->id();
        $table->string('image'); // Path gambar
        $table->string('alt')->nullable(); // Teks alternatif (SEO)
        $table->integer('position')->default(0); // Urutan slider
        $table->boolean('is_active')->default(true); // Status aktif/tidak
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
