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
            $table->string('username',200);
            $table->string('nama_tim',200);
            $table->string('nama_lapangan',200);
            $table->string('kode_lapangan',200);
            $table->double('harga_lapangan');
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
