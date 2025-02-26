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
        Schema::create('kias', function (Blueprint $table) {
            $table->id();
            $table->string('no_lp');
            $table->date('tanggal_lp');
            $table->string('hasil_tangkapan')->nullable();
            $table->string('jenis_kasus', 100)->nullable();
            $table->longText('kronologis')->nullable();

            $table->longText('tersangka_nama')->nullable();
            $table->longText('tersangka_nik')->nullable();
            $table->longText('tersangka_warganegara')->nullable();
            $table->longText('tersangka_suku')->nullable();
            $table->longText('tersangka_jk')->nullable();
            $table->longText('tersangka_tempat_tgl_lahir')->nullable();
            $table->longText('tersangka_umur')->nullable();
            $table->longText('tersangka_agama')->nullable();
            $table->longText('tersangka_pekerjaan')->nullable();
            $table->longText('tersangka_alamat')->nullable();

            $table->string('korban')->nullable();

            $table->longText('saksi_nama')->nullable();
            $table->longText('saksi_nik')->nullable();
            $table->longText('saksi_warganegara')->nullable();
            $table->longText('saksi_suku')->nullable();
            $table->longText('saksi_jk')->nullable();
            $table->longText('saksi_tempat_tgl_lahir')->nullable();
            $table->longText('saksi_umur')->nullable();
            $table->longText('saksi_agama')->nullable();
            $table->longText('saksi_pekerjaan')->nullable();
            $table->longText('saksi_alamat')->nullable();

            $table->longText('melanggar_pasal')->nullable();
            $table->longText('barang_bukti')->nullable();
            $table->integer('kerugian')->nullable();
            $table->string('penyidik')->nullable();
            $table->string('penanganan_perkara')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kias');
    }
};
