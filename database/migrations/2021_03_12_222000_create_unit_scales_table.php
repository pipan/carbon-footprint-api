<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitScalesTable extends Migration
{
    public function up()
    {
        Schema::create('unit_scales', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('unit_id')->unsigned();
            $table->integer('multiplier')->unsigned()->default(1);
            $table->integer('devider')->unsigned()->default(1);
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
        Schema::dropIfExists('unit_scales');
    }
}
