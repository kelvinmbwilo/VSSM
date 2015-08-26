<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manufactures';
    protected  $guarded = array('id');

    public function vaccine(){
        return $this->hasMany('App\PackagingInformation', 'manufacture_id', 'id');
    }
}
