<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecoltesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recoltes', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('poids');
            $table->integer('idRuche')->unsigned();
            $table->foreign('idRuche')->references('id')->on('ruches')
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
        Schema::dropIfExists('recoltes');
    }
}
