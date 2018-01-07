<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruches', function (Blueprint $table) {
        $table->increments('id');
        $table->string('longitude');
        $table->string('latitude');
        $table->string('titre');
        $table->integer('idUser')->unsigned();
        $table->foreign('idUser')->references('id')->on('users')
              ->onDelete('restrict')
              ->onUpdate('restrict');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ruches');
    }
}
