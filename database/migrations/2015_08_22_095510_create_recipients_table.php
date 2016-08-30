<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level');
            $table->integer('parent_id');
            $table->string('code');
            $table->string('name');
            $table->string('transport_mode_id');
            $table->string('population');
            $table->string('population_year');
            $table->string('notes');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('distance');
            $table->string('status');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('recipients')->insert([
            'level' => 1,
            'parent_id' => 0,
            'code' => 'ORG1',
            'name' => 'Central Level',
            'transport_mode_id' => '',
            'population' => '',
            'population_year' => '',
            'notes' => '',
            'longitude' => '12.0962041',
            'latitude' => '-86.2584606',
            'distance' => '',
            'status' => 'active',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipients');
    }
}
