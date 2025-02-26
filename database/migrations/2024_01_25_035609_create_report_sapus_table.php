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
        Schema::create('report_sapus', function (Blueprint $table) {
            $table->id();
            $table->integer('polda_id');
            $table->json('personil_ket')->comment("['Ditpolairud', 'Sarlinmas', 'Relawan Pantas', 'Nelayan','Pelaku Kuliner','FPRB']");
            $table->json('personil_jml')->comment("[30,5,15,15,10,5]");
            $table->json('personil_sat')->comment("['Pers','Pers', 'Pers', 'Pers','Pers', 'Pers']");
            $table->longText('lokasi')->nullable();
            $table->double('sampah_organik_jml')->nullable()->comment('Kg');
            $table->longText('sampah_organik_ket')->nullable();
            $table->double('sampah_anorganik_jml')->nullable()->comment("Kg");
            $table->longText('sampah_anorganik_ket')->nullable();
            $table->longText('keterangan')->nullable();
            $table->longText('foto')->nullable();
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_sapus');
    }
};
