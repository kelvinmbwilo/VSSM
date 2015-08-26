<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'arrivals';
    protected  $guarded = array('id');

    public function fromSource(){
        return $this->belongsTo('App\Recipient', 'recipient_source_id', 'id');
    }

    public function destination(){
        return $this->belongsTo('App\Recipient', 'recipient_destination_id', 'id');
    }

    public function source(){
        return $this->belongsTo('App\Source', 'source_id', 'id');
    }

    public function arrival(){
        return $this->belongsTo('App\Arrival', 'arrival_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'receiving_user', 'id');
    }

    public function arrivalItems(){
        return $this->hasMany('App\ArrivalItem', 'arrival_id', 'id');
    }
}
