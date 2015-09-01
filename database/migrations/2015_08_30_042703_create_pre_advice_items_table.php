<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreAdviceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_advice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->integer('item_id');
            $table->integer('packaging_id');
            $table->integer('number_of_doses');
            $table->string('lot_number');
            $table->date('expired_date');
            $table->string('status');
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
        Schema::drop('pre_advice_items');
    }
}
