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
            $table->integer('msg_type'); // AIS Message Type (1-3, 4, 9, 11)
            $table->integer('repeat_indicator');
            $table->bigInteger('mmsi');
            $table->integer('nav_status')->nullable();
            $table->boolean('rot_over_range')->nullable();
            $table->float('rot', 8, 3)->nullable();
            $table->float('sog', 6, 3)->nullable();
            $table->boolean('position_accuracy')->nullable();
            $table->decimal('x', 10, 6)->nullable(); // Longitude
            $table->decimal('y', 10, 6)->nullable(); // Latitude
            $table->float('cog', 6, 3)->nullable();
            $table->integer('true_heading')->nullable();
            $table->integer('timestamp')->nullable();
            $table->integer('special_manoeuvre')->nullable();
            $table->boolean('raim')->nullable();
            $table->integer('sync_state')->nullable();
            $table->integer('slot_increment')->nullable();
            $table->integer('slots_to_allocate')->nullable();
            $table->boolean('keep_flag')->nullable();
            $table->integer('altitude')->nullable(); // Untuk SAR Aircraft (Type 9)
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
