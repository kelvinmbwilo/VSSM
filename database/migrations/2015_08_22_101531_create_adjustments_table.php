<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vaccine_id');
            $table->integer('packaging_id');
            $table->string('lot_number');
            $table->date('expiry_date');
            $table->integer('store_id');
            $table->integer('activity_id');
            $table->integer('source_id');
            $table->integer('recipient_id');
            $table->string('notes');
            $table->string('adjustment_reason');
            $table->string('adjustment_type');
            $table->integer('resource_id');
            $table->integer('order_no');
            $table->integer('year');
            $table->string('reference');
            $table->string('previous_amount');
            $table->string('current_amount');
            $table->string('adjusted_volume');
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
        Schema::drop('adjustments');
    }
}
