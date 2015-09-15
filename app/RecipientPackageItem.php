<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipientPackageItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients_package_items';
    protected  $guarded = array('id');

    public function package(){
        return $this->belongsTo('App\RecipientPackage', 'package_id', 'id');
    }

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'vaccine_id', 'id');
    }

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

    public function receiver(){
        return $this->belongsTo('App\Recipient', 'receiver_id', 'id');
    }

    public function packaging(){
        return $this->belongsTo('App\PackagingInformation', 'packaging_id', 'id');
    }

    public function activities(){
        return $this->belongsTo('App\Activity', 'activity', 'id');
    }

    public function store(){
        return $this->belongsTo('App\Store', 'store_id', 'id');
    }

}
