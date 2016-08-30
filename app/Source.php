<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sources';
    protected  $guarded = array('id');

    public function arrivals(){
        return $this->hasMany('App\Arrival', 'source_id', 'id');
    }
}
