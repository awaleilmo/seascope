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
        Schema::create('giats', function (Blueprint $table) {
            $table->id();
            $table->integer('lokasi_id');
            $table->integer('sambang')->default(0);
            $table->integer('rw')->default(0);
            $table->integer('perpustakaan')->default(0);
            $table->integer('klinik')->default(0);
            $table->integer('sampah')->default(0);
            $table->longText('keterangan')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('bulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giats');
    }
};
