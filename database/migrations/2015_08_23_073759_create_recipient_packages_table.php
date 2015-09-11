<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_number');
            $table->integer('source_id');
            $table->integer('recipient_id');
            $table->date('date_sent');
            $table->date('date_received');
            $table->integer('transport_mode_id');
            $table->string('request_number');
            $table->integer('sending_user');
            $table->integer('receiving_user');
            $table->string('receiving_status');
            $table->string('comments');
            $table->string('year');
            $table->string('order_number');
            $table->string("status");
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
        Schema::drop('recipients_packages');
    }
}
