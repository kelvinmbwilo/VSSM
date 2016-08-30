<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vaccines';
    protected  $guarded = array('id');

    public function arrivalItems(){
        return $this->hasMany('App\ArrivalItem', 'vaccine_id', 'id');
    }

    public function stock(){
        return $this->hasMany('App\Stock', 'vaccine_id', 'id');
    }

    public function storeStock(){
        return $this->hasMany('App\StoreStock', 'vaccine_id', 'id');
    }

    public function packageItems(){
        return $this->hasMany('App\RecipientPackageItem', 'vaccine_id', 'id');
    }

    public function itemsMinMax(){
        return $this->hasMany('App\ItemMinMax', 'item_id', 'id');
    }

    public function annualQuota(){
        return $this->hasMany('App\RecipientAnnualQuota', 'item_id', 'id');
    }

    public function packaging(){
        return $this->hasMany('App\PackagingInformation', 'vaccine_id', 'id');
    }

}
