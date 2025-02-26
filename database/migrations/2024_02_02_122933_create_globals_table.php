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
        Schema::create('globals', function (Blueprint $table) {
            $table->id();
            $table->string('id_no_lp',100);
            $table->date('tanggal_lp');
            $table->string('hasil_tangkapan',100)->nullable();
            $table->string('jenis_kasus',100)->nullable();
            $table->text('kronologi')->nullable();
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
            $table->text('korban',100)->nullable();
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
            $table->text('melanggar_pasal')->nullable();
            $table->longText('barang_bukti')->nullable();
            $table->decimal('kerugian',17,2)->nullable();
            $table->text('ba_serah_terima')->nullable();
            $table->string('keterangan',100)->nullable();
            $table->string('created_by',10)->nullable();
            $table->string('updated_by',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('globals');
    }
};
