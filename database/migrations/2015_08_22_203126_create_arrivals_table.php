<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrivals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recipient_source_id');
            $table->integer('recipient_destination_id');
            $table->integer('source_id');
            $table->string('arrival_report_number');
            $table->string('package_volume');
            $table->string('total_weight');
            $table->date('arrival_date');
            $table->string('freight_cost');
            $table->string('receiving_user');
            $table->string('notes');
            $table->string('main_currency');
            $table->string('used_currency');
            $table->string('exchange_rate');
            $table->string('reference');
            $table->integer('order_no');
            $table->string('status');
            $table->string('year');
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
        Schema::drop('arrivals');
    }
}
