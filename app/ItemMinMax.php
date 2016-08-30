<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemMinMax extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'items_min_max';
    protected  $guarded = array('id');

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'item_id', 'id');
    }

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

}
