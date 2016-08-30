<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportMode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transport_modes';
    protected  $guarded = array('id');

    public function packages(){
        return $this->hasMany('App\RecipientPackage', 'transport_mode_id', 'id');
    }
}
