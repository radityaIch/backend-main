<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_permit_id')->constrained('travel_permits')->onDelete('cascade');
            $table->string('keterangan');
            $table->string('kendala');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel_trackings');
    }
}
