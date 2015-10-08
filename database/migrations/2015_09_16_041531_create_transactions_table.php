<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_type');
            $table->integer('vaccine_id');
            $table->integer('packaging_id');
            $table->integer('recipient_id');
            $table->integer('origin_amount');
            $table->integer('updated_amount');
            $table->string('month');
            $table->integer('year');
            $table->string('transaction_number');
            $table->integer('order_no');
            $table->string('lot_number');
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
        Schema::drop('transactions');
    }
}
