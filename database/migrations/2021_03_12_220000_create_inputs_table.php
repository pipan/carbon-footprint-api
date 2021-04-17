<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputsTable extends Migration
{
    public function up()
    {
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('model_id')->unsigned();
            $table->string('name');
            $table->string('reference', 32);
            $table->integer('unit_id')->unsigned();
            $table->integer('default_value')->unsigned();
            $table->timestamps();

            $table->index(['model_id'], 'model_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inputs');
    }
}
