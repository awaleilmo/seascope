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
        Schema::create('report_rws', function (Blueprint $table) {
            $table->id();
            $table->integer('polda_id');
            $table->json('personil_jml')->comment("[1,2,3,4,5]");
            $table->json('personil_satu')->comment("['sat','sat','sat','sat','sat']");
            $table->longText('sambang')->nullable();
            $table->longText('pemecahaan')->nullable();
            $table->longText('informasi')->nullable();
            $table->longText('penanganan')->nullable();
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
        Schema::dropIfExists('report_rws');
    }
};
