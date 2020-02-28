<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_booking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_booking');
            $table->string('id_katalog');
            $table->string('id_user');
            $table->integer('status')->default('1');
            $table->string('status_konfirmasi')->default('belum');
            $table->string('status_produk');
            $table->datetime('tanggal_batal')->nullable();
            $table->timestamps();
            $table->datetime('tanggal_sewa_awal');
            $table->datetime('tanggal_sewa_akhir');
            $table->datetime('tanggal_bayar')->nullable();
            $table->string('status_bayar')->default('BELUM BAYAR');
            $table->datetime('tanggal_kembali')->nullable();
            $table->string('user_batal')->nullable();
            $table->string('alasan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_booking');
    }
}
