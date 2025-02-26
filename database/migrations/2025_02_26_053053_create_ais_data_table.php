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
        Schema::create('ais_data', function (Blueprint $table) {
            $table->id();
            $table->integer('msg_type')->nullable();
            $table->integer('repeat')->nullable();
            $table->bigInteger('mmsi')->nullable();
            $table->integer('status')->nullable();
            $table->float('turn')->nullable();
            $table->float('speed')->nullable();
            $table->boolean('accuracy')->nullable();
            $table->double('lon', 10, 6)->nullable();
            $table->double('lat', 10, 6)->nullable();
            $table->float('course')->nullable();
            $table->integer('heading')->nullable();
            $table->integer('second')->nullable();
            $table->integer('maneuver')->nullable();
            $table->string('spare_1')->nullable();
            $table->boolean('raim')->nullable();
            $table->bigInteger('radio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ais_data');
    }
};
