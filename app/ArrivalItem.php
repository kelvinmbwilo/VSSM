<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArrivalItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'arrival_items';
    protected  $guarded = array('id');

    public function source(){
        return $this->belongsTo('App\Recipient', 'recipient_source_id', 'id');
    }

    public function destination(){
        return $this->belongsTo('App\Recipient', 'recipient_destination_id', 'id');
    }

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'vaccine_id', 'id');
    }

    public function packaging(){
        return $this->belongsTo('App\PackagingInformation', 'packaging_id', 'id');
    }

    public function activity(){
        return $this->belongsTo('App\Activity', 'activity_id', 'id');
    }

    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

    public function arrival(){
        return $this->belongsTo('App\Arrival', 'arrival_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'receiving_user', 'id');
    }
}
