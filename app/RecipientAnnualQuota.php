<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipientAnnualQuota extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipient_annual_quota';
    protected  $guarded = array('id');

    public function recipient(){
        return $this->belongsTo('App\Recipient', 'recipient_id', 'id');
    }

    public function vaccine(){
        return $this->belongsTo('App\Vaccine', 'item_id', 'id');
    }


}
