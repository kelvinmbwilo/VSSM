<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_store');
            $table->integer('to_store');
            $table->integer('store_item_id');
            $table->integer('user_id');
            $table->integer('amount');
            $table->integer('recipient_id');
            $table->integer('vaccine_id');
            $table->string('lot_number');
            $table->date('expiry_date');
            $table->string('moved_volume');
            $table->string('year');
            $table->string('reference');
            $table->integer('order_no');
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
        Schema::drop('item_movements');
    }
}
