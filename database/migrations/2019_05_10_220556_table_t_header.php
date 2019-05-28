<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableTHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_header', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('tanggal',30);
			$table->string('order_no',50);
            $table->string('username',100);
            $table->string('kode_tim',100);
            $table->string('kode_lapangan',50);
            $table->string('nama_lapangan',100);
			$table->double('harga_lapangan');
            $table->double('total_harga'); //total_harga = total_jam * harga_lapangan
			$table->integer('total_jam');
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
        Schema::dropIfExists('t_header');
    }
}
