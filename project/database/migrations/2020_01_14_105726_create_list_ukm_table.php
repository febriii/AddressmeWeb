<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListUKMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_ukm', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_ukm')->unique();
            $table->string('nama_ukm');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('website');
            $table->string('lat');
            $table->string('lng');
            $table->string('gambar_ukm');
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
        Schema::dropIfExists('list_ukm');
    }
}
