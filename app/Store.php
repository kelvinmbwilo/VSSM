<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stores';
    protected  $guarded = array('id');

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

    public function arrivalItems(){
        return $this->hasMany('App\ArrivalItem', 'store_id', 'id');
    }

    public function packageItems(){
        return $this->hasMany('App\RecipientPackageItem', 'store_id', 'id');
    }

    public function inItemMovements(){
        return $this->hasMany('App\ItemMovement', 'to_store', 'id');
    }

    public function outItemMovements(){
        return $this->hasMany('App\ItemMovement', 'from_store', 'id');
    }
}
