<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange_rate extends Model
{
    public $timestamps = false;
    public $primaryKey = 'rate_id';
    
    public function currency_dict() {
        return $this->hasOne(Currency_dict::class, 'currency_id', 'currency_id');
    }
}
