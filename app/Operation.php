<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    public $timestamps = false;
    public $primaryKey = 'oper_id';
    
    public function wallet_hist() {
        return $this->belongsTo(Wallet_hist::class, 'oper_id', 'oper_id');
    }
}
