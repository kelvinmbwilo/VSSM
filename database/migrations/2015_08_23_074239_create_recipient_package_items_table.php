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
