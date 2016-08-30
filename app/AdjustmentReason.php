<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustmentReason extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adjustment_reasons';
    protected  $guarded = array('id');

}
