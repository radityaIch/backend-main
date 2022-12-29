<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelPermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel_permits', function (Blueprint $table) {
            $table->id();
            $table->string('no_do');
            $table->string('pengirim');
            $table->string('alamat_muat');
            $table->string('alamat_kirim');
            $table->string('no_telp');
            $table->string('nopol');
            $table->string('driver');
            $table->string('unit');
            $table->string('pengiriman');
            $table->integer('harga_jual');
            $table->integer('harga_beli');
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
        Schema::dropIfExists('travel_permits');
    }
}
