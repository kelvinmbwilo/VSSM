<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('language');
            $table->string('main_currency');
            $table->string('voucher_tittle');
            $table->string('start_year');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('system_settings')->insert([
            'language' => 'enUS',
            'main_currency' => '$',
            'start_year' => '01',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_settings');
    }
}
