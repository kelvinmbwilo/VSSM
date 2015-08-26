<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipientLevel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipient_levels';
    protected  $guarded = array('id');

    public function recipients(){
        return $this->hasMany('App\Recipient', 'level', 'order');
    }
}
