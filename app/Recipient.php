<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients';
    protected  $guarded = array('id');

    public function level(){
        return $this->belongsTo('App\RecipientLevel', 'level', 'order');
    }

    public function childrens(){
        return $this->hasMany('App\Recipient', 'parent_id', 'id');
    }

    public function parent(){
        return $this->belongsTo('App\Recipient', 'id', 'parent_id');
    }

    public function arrivalItems(){
        return $this->hasMany('App\ArrivalItem', 'recipient_destination_id', 'id');
    }

    public function sentItems(){
        return $this->hasMany('App\ArrivalItem', 'recipient_source_id', 'id');
    }

    public function arrivalPackage(){
        return $this->hasMany('App\Arrival', 'recipient_destination_id', 'id');
    }

    public function sentPackages(){
        return $this->hasMany('App\Arrival', 'recipient_source_id', 'id');
    }


    public function packages(){
        return $this->hasMany('App\RecipientPackage', 'recipient_id', 'id');
    }

    public function stock(){
        return $this->hasMany('App\Stock', 'recipient_id', 'id');
    }

    public function stores(){
        return $this->hasMany('App\Store', 'recipient_id', 'id');
    }


}
