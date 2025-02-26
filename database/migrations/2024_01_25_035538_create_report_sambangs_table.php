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
        Schema::create('report_sambangs', function (Blueprint $table) {
            $table->id();
            $table->integer('polda_id');
            $table->json('personil_jml')->comment('[1,2,3]');
            $table->json('personil_sat')->comment("['pers','pers','pers']");
            $table->longText('lokasi')->nullable();
            $table->bigInteger('jarak')->nullable();
            $table->longText('uraian')->nullable();
            $table->integer('jml_peserta')->nullable();
            $table->longText('keterangan')->nullable();
            $table->enum('tipe', [1, 2, 3])->comment('binaan, sentuhan, pantauan');
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
        Schema::dropIfExists('report_sambangs');
    }
};
