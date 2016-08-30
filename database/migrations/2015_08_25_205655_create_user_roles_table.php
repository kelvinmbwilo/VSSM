<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('roles');
            $table->string('status');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('user_roles')->insert([
            'name' => 'Administrator',
            'status' => 'active',
            'roles' => 'see_dashboard:see_closetoexpiry:see_belowminimum:see_aboveminimum:see_vaccinediluent:see_preadvice:see_dispach:see_expectedpackages:see_dispatchedpackage:see_stockitems:see_arrivalsummary:see_arivalcancellation:see_dispatchadjustment:see_stockadjustment:see_moveitems:see_dispatchreprt:see_arrivalreport:see_expireditems:see_canceledinvoice:see_intransityinvertory:see_adjustmentreport:see_itemmovedreport::see_stockopening:see_preadvicenotice:add_preadvicenotice:update_preadvicenotice:delete_preadvicenotice:see_recipients:add_recipients:update_recipients:delete_recipients:see_recipientslevels:add_recipientslevels:update_recipientslevels:delete_recipientslevels:see_users:add_users:update_users:delete_users:see_userroles:add_userroles:update_userroles:delete_userroles:see_configuration:update_configuration:see_vaccine:add_vaccine:edit_vaccine:delete_vaccine:see_packaging:add_packaging:edit_packaging:delete_packaging:see_itemsmaxmin:add_itemsmaxmin:edit_itemsmaxmin:delete_itemsmaxmin:see_sources:add_sources:edit_sources:delete_sources:see_stores:add_stores:edit_stores:delete_stores:see_transportmode:add_transportmode:edit_transportmode:delete_transportmode:see_adjustmentreason:add_adjustmentreason:edit_adjustmentreason:delete_adjustmentreason:see_activities:add_activities:edit_activities:delete_activities:see_manufactures:add_manufactures:edit_manufactures:delete_manufactures:see_basicrecipients:add_basicrecipients:edit_basicrecipients:delete_basicrecipients:see_annualqouta:add_annualqouta:edit_annualqouta:delete_annualqouta',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_roles');
    }
}
