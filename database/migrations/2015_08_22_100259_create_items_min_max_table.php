<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsMinMaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_min_max', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->string('recipient_id');
            $table->string('min_value');
            $table->string('max_value');
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
        Schema::drop('items_min_max');
    }
}
