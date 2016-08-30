<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientAnnualQuotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_annual_quota', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('recipient_id');
            $table->integer('item_id');
            $table->string('expected_annual_need');
            $table->string('year');
            $table->integer('parent_id');
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
        Schema::drop('recipient_annual_quota');
    }
}
