<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemMovement extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'item_movements';
    protected  $guarded = array('id');

    public function fromStore(){
        return $this->belongsTo('App\Store', 'from_store', 'id');
    }

    public function toStore(){
        return $this->belongsTo('App\Store', 'to_store', 'id');
    }

    public function item(){
        return $this->belongsTo('App\StoreStock', 'store_item_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

}
