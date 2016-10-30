<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = false;
    public $primaryKey='tran_id';
    
    public function wallet_from() {
        return $this->hasOne(Wallet::class, 'wallet_id', 'wallet_id_from');
    }
    
    public function wallet_to() {
        return $this->hasOne(Wallet::class, 'wallet_id', 'wallet_id_to');
    }
    
    public function currency_dict() {
        return $this->hasOne(Currency_dict::class, 'currency_id', 'currency_id');
    }
}
