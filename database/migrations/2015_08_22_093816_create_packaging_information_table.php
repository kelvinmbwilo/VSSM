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
            $table->string('dose_per_vial');
            $table->string('vials_per_box');
            $table->string('cm_per_dose');
            $table->string('manufacture_id');
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
