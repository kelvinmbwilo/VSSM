<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreStock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'store_stocks';
    protected  $guarded = array('id');


    public function packaging(){
        return $this->belongsTo('App\RecipientPackage', 'packaging_id', 'id');
    }

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'vaccine_id', 'id');
    }

    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

}
