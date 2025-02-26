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
            $table->bigInteger('mmsi')->unique();
            $table->string('shipname')->nullable();
            $table->integer('ship_type')->nullable();
            $table->integer('to_bow')->nullable();
            $table->integer('to_stern')->nullable();
            $table->integer('to_port')->nullable();
            $table->integer('to_starboard')->nullable();
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
