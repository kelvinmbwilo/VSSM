<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order');
            $table->string('name');
            $table->string('status');
            $table->string('code');
            $table->timestamps();
        });

        for($i=1;$i<=4;$i++){
            \Illuminate\Support\Facades\DB::table('recipient_levels')->insert([
                'order' => $i,
                'name' => 'Level'.$i,
                'status' => 'active',
                'code' => 'Level'.$i,
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipient_levels');
    }
}
