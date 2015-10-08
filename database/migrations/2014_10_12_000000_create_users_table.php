<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('recipient_id');
            $table->string('role');
            $table->string('user_name')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('status');
            $table->rememberToken();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('users')->insert([
            'first_name' => 'Admin',
            'middle_name' => '',
            'last_name' => 'User',
            'phone' => '',
            'recipient_id' => 1,
            'role' => 1,
            'user_name' => 'admin',
            'email' => 'adminuser@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin'),
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
        Schema::drop('users');
    }
}
