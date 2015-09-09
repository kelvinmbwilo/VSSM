<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreAdviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_advice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');
            $table->string('package_id');
            $table->date('expected_time_of_arrival');
            $table->float('total_weight');
            $table->integer('item_id');
            $table->integer('packaging_id');
            $table->integer('number_of_doses');
            $table->string('lot_number');
            $table->date('manufacture_date');
            $table->date('expired_date');
            $table->string('status');
            $table->float('packed_volume');
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
        Schema::drop('pre_advice');
    }
}
