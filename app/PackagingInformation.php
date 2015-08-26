<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagingInformation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packaging_information';
    protected  $guarded = array('id');

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'vaccine_id', 'id');
    }

    public function arrivalItems(){
        return $this->hasMany('App\ArrivalItem', 'packaging_id', 'id');
    }

    public function stock(){
        return $this->hasMany('App\Stock', 'packaging_id', 'id');
    }

    public function storeStock(){
        return $this->hasMany('App\StoreStock', 'packaging_id', 'id');
    }

    public function packageItems(){
        return $this->hasMany('App\RecipientPackageItem', 'packaging_id', 'id');
    }

}
