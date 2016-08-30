<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientPackageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients_package_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->integer('packaging_id');
            $table->integer('vaccine_id');
            $table->string('batch_number');
            $table->string('VVM stage');
            $table->string('activity');
            $table->integer('store_id');
            $table->integer('source_id');
            $table->integer('amount');
            $table->integer('unit_price');
            $table->date('expiry_date');
            $table->integer('recipient_id');
            $table->integer('receiver_id');
            $table->date('date_sent');
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
        Schema::drop('recipients_package_items');
    }
}
