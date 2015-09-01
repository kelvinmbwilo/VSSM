<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging_information', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vaccine_id');
            $table->string('GTIN');
            $table->integer('dose_per_vial');
            $table->integer('vials_per_box');
            $table->string('cm_per_dose');
            $table->string('commercial_name');
            $table->integer('manufacture_id');
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
        Schema::drop('packaging_information');
    }
}
