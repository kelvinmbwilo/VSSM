<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activities';
    protected  $guarded = array('id');

    public function arrivalItem(){
        return $this->hasMany('App\ArrivalItem', 'activity_id', 'id');
    }
}
