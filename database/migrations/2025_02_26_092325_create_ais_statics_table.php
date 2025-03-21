<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ais_statics', function (Blueprint $table) {
            $table->id();
            $table->integer('msg_type')->description('AIS Message Type (5, 24)');
            $table->integer('repeat_indicator')->nullable()->description('indikator pengulangan (0-3)');
            $table->bigInteger('mmsi')->nullable()->description('nomor indetifikasi unik kapal');
            $table->integer('ais_version')->nullable()->description('versi AIS');
            $table->bigInteger('imo_number')->nullable()->description('nomor IMO');
            $table->string('call_sign', 10)->nullable()->description('call sign kapal');
            $table->string('ship_name', 50)->nullable()->description('nama kapal');
            $table->integer('ship_type')->nullable()->description('jenis kapal');
            $table->string('vendor_id', 7)->nullable()->description('ID vendor perangkat (AIS message type 24)');
            $table->integer('dimension_to_bow')->nullable()->description('jarak ke haluan');
            $table->integer('dimension_to_stern')->nullable()->description('jarak ke buritan');
            $table->integer('dimension_to_port')->nullable()->description('jarak ke sisi kiri');
            $table->integer('dimension_to_starboard')->nullable()->description('jarak ke sisi kanan');
            $table->integer('fix_type')->nullable();
            $table->integer('eta_month')->nullable();
            $table->integer('eta_day')->nullable();
            $table->integer('eta_hour')->nullable();
            $table->integer('eta_minute')->nullable();
            $table->float('draught', 5, 2)->nullable();
            $table->string('destination', 100)->nullable();
            $table->boolean('dte')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ais_statics');
    }
};
