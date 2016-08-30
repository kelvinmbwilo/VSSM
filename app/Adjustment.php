<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adjustments';
    protected  $guarded = array('id');

}
