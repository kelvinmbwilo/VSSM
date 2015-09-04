<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemSettings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'system_settings';
    protected  $guarded = array('id');
}
