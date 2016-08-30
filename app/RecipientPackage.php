<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipientPackage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients_packages';
    protected  $guarded = array('id');

    public function source(){
        return $this->belongsTo('App\Recipient', 'source_id', 'id');
    }

    public function destination(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

    public function items(){
        return $this->hasMany('App\RecipientPackageItem', 'package_id', 'id');
    }

    public function transport(){
        return $this->belongsTo('App\TransportMode', 'transport_mode_id', 'id');
    }

    public function arrival(){
        return $this->belongsTo('App\Arrival', 'arrival_id', 'id');
    }

    public function sendingUser(){
        return $this->belongsTo('App\User', 'sending_user', 'id');
    }

    public function receivingUser(){
        return $this->belongsTo('App\User', 'receiving_user', 'id');
    }
}
