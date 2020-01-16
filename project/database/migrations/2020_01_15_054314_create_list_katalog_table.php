<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListKatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_katalog', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_katalog')->unique();
            $table->string('id_ukm');
            $table->string('judul_katalog');
            $table->string('usia');
            $table->string('ukuran');
            $table->string('stok_katalog');
            $table->string('stok_produk');
            $table->string('harga_katalog');
            $table->string('gambar_katalog');
            $table->string('user_ubah')->nullable();
            $table->timestamps();
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_katalog');
    }
}
