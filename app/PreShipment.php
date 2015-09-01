<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreShipment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pre_advice';
    protected  $guarded = array('id');

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'item_id', 'id');
    }

    public function packaging(){
        return $this->belongsTo('App\PackagingInformation', 'packaging_id', 'id');
    }

    public function source(){
        return $this->belongsTo('App\Source', 'source_id', 'id');
    }

}
