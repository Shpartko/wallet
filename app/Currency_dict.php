<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency_dict extends Model
{
    public $timestamps = false;
    public $primaryKey = 'currency_id';
    
    /*
    public function wallet() {
        return $this->belongsTo(Wallet::class, 'currency_id', 'currency_id');
    }
    
    public function exchange_rate() {
        return $this->belongsTo(Exchange_rate::class, 'currency_id', 'currency_id');
    }*/
}
