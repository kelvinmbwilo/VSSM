<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vaccine_id');
            $table->integer('store_id');
            $table->integer('amount');
            $table->string('lot_number');
            $table->string('packaging_id');
            $table->date('expiry_date');
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
        Schema::drop('store_stocks');
    }
}
