<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log';
    protected  $guarded = array('id');

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
