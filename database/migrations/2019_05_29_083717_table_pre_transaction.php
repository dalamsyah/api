<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablePreTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_transaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('tanggal',30);
			$table->string('order_no',50);
            $table->string('username',100);
            $table->string('kode_tim',100);
            $table->string('kode_lapangan',50);
            $table->string('nama_lapangan',100);
			$table->double('harga_lapangan');
            $table->string('jam_mulai',20);
			$table->string('jam_selesai',20);
			$table->integer('total_jam');
            $table->double('total_harga'); //total_harga = total_jam * harga_lapangan
			$table->string('status', 30); //failed, success, waiting
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
        Schema::dropIfExists('pre_transaksi');
    }
}
