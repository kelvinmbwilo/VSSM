<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrivalItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrival_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recipient_source_id');
            $table->integer('recipient_destination_id');
            $table->integer('vaccine_id');
            $table->integer('packaging_id');
            $table->integer('activity_id');
            $table->integer('store_id');
            $table->integer('arrival_id');
            $table->string('package_number');
            $table->string('lot_number');
            $table->string('number_as_expected');
            $table->string('number_expected');
            $table->string('number_received');
            $table->string('physical_damage');
            $table->string('damaged_amount');
            $table->string('vvm_status');
            $table->string('receiving_user');
            $table->string('notes');
            $table->string('unit_price');
            $table->string('total_price');
            $table->string('volume');
            $table->integer('manufacture_id');
            $table->date('prduction_date');
            $table->date('expiry_date');
            $table->integer('source_id');
            $table->date('arrival_date');
            $table->string('voucher_number');
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
        Schema::drop('arrival_items');
    }
}
