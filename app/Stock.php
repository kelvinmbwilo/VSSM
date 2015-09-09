<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stock';
    protected  $guarded = array('id');

    public function packaging(){
        return $this->belongsTo('App\RecipientPackage', 'packaging_id', 'id');
    }

    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'vaccine_id', 'id');
    }

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

}
